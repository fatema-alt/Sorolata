<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\StockMovementType;
use App\Http\Controllers\Controller;
use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\OrderTimeline;
use App\Models\Product;
use App\Services\Admin\ActivityLogService;
use App\Services\Commerce\InventoryService;
use App\Services\Commerce\OrderService;
use App\Services\Logistics\SteadfastCourierService;
use App\Services\Notification\SmsService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminOrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected ActivityLogService $activityLog,
        protected SmsService $smsService,
        protected SteadfastCourierService $steadfastCourier,
        protected InventoryService $inventoryService
    ) {}

    public function index(Request $request): View
    {
        $query = Order::with(['items.product', 'user']);

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('order_number', 'like', "%{$term}%")
                  ->orWhere('customer_name', 'like', "%{$term}%")
                  ->orWhere('customer_phone', 'like', "%{$term}%")
                  ->orWhere('customer_email', 'like', "%{$term}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        $orders = $query->latest('created_at')->paginate(15)->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
        ]);
    }

    public function show(int $id): View
    {
        $order = Order::with([
            'items.product.media',
            'items.variant',
            'timelines' => fn ($q) => $q->orderBy('created_at', 'desc'),
            'shippingRate',
            'returns',
        ])->findOrFail($id);

        return view('admin.orders.show', [
            'order' => $order,
        ]);
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $newStatus = OrderStatus::tryFrom($validated['status']) ?? OrderStatus::CONFIRMED;

        $this->orderService->transitionStatus(
            order: $order,
            newStatus: $newStatus,
            note: $validated['note'] ?? "Fulfillment updated to {$newStatus->label()}",
            actorType: 'staff',
            actorId: auth()->id()
        );

        $this->activityLog->log(
            action: 'order.status_updated',
            description: "Updated order #{$order->order_number} status to {$newStatus->value}",
            subject: $order
        );

        return back()->with('success', "Order #{$order->order_number} status updated to {$newStatus->label()}.");
    }

    public function updateShipping(Request $request, int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'tracking_number' => 'required|string|max:100',
            'courier_name' => 'required|string|max:100',
        ]);

        $order->update($validated);

        $this->orderService->transitionStatus(
            order: $order,
            newStatus: OrderStatus::SHIPPED,
            note: "Dispatched via {$validated['courier_name']}. Tracking: {$validated['tracking_number']}",
            actorType: 'staff',
            actorId: auth()->id()
        );

        // SMS Dispatch
        try {
            $this->smsService->sendOrderShippedSms($order);
        } catch (\Throwable $e) {
            Log::warning("Order shipped SMS dispatch failed: " . $e->getMessage());
        }

        return back()->with('success', "Courier tracking details saved for Order #{$order->order_number}.");
    }

    public function invoice(int $id): View
    {
        $order = Order::with(['items.product', 'shippingRate'])->findOrFail($id);

        return view('admin.orders.invoice', [
            'order' => $order,
        ]);
    }

    public function verifyPayment(Request $request, int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'transaction_id' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:500',
        ]);

        $trxId = $validated['transaction_id'] ?: ($order->transaction_id ?: 'MANUAL_' . strtoupper(\Illuminate\Support\Str::random(8)));
        $isCodWithAdvance = $order->advance_delivery_charge > 0 && in_array(strtolower($order->payment_gateway?->value ?? ''), ['cash_on_delivery', 'cod'], true);

        if ($isCodWithAdvance) {
            $advanceFee = (float) $order->advance_delivery_charge;
            $order->paid_amount = $advanceFee;
            $order->due_amount = max(0.0, (float) $order->total_amount - $advanceFee);
            $order->payment_status = PaymentStatus::PARTIALLY_PAID;
        } else {
            $order->paid_amount = (float) $order->total_amount;
            $order->due_amount = 0.00;
            $order->payment_status = PaymentStatus::PAID;
        }

        $order->status = OrderStatus::CONFIRMED;
        $order->transaction_id = $trxId;

        $paymentDetails = $order->payment_details ?? [];
        $paymentDetails['verified_by'] = auth()->user()?->name ?? 'Admin Staff';
        $paymentDetails['verified_at'] = now()->toIso8601String();
        $paymentDetails['verified_trx_id'] = $trxId;
        if (!empty($validated['note'])) {
            $paymentDetails['admin_note'] = $validated['note'];
        }
        $order->payment_details = $paymentDetails;
        $order->save();

        $curatorName = auth()->user()?->name ?? 'Administrator';
        $timelineNote = $isCodWithAdvance
            ? "Advance delivery fee of ৳{$order->paid_amount} verified by Curator [{$curatorName}] (TrxID: {$trxId}). Order confirmed! Remaining ৳{$order->due_amount} due on parcel delivery."
            : "Payment of ৳{$order->paid_amount} verified & confirmed by Curator [{$curatorName}] (TrxID: {$trxId}). Order confirmed for atelier dispatch.";

        \App\Models\OrderTimeline::create([
            'order_id' => $order->id,
            'status' => $order->status->value,
            'actor_type' => 'staff',
            'note' => $timelineNote,
            'customer_notified' => true,
        ]);

        $this->activityLog->log(
            action: 'order.payment_verified',
            description: "Curator verified payment for Order #{$order->order_number} (TrxID: {$trxId})",
            subject: $order
        );

        // SMS Dispatch on Payment Verification
        try {
            $this->smsService->sendOrderConfirmedSms($order);
        } catch (\Throwable $e) {
            Log::warning("Order confirmed SMS dispatch failed: " . $e->getMessage());
        }

        return back()->with('success', "Order #{$order->order_number} payment verified and order confirmed successfully!");
    }

    public function dispatchSteadfast(Request $request, int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);

        $booking = $this->steadfastCourier->createOrder($order);

        if (!$booking['success']) {
            return back()->with('error', "Steadfast Courier Booking Failed: " . ($booking['message'] ?? 'Unknown error'));
        }

        $trackingCode = $booking['tracking_code'];
        $order->courier_name = 'Steadfast Courier';
        $order->tracking_number = $trackingCode;
        $order->status = OrderStatus::SHIPPED;
        $order->save();

        $curatorName = auth()->user()?->name ?? 'Staff';
        OrderTimeline::create([
            'order_id' => $order->id,
            'status' => $order->status->value,
            'actor_type' => 'staff',
            'note' => "Dispatched via Steadfast Courier by [{$curatorName}]. Tracking Code: {$trackingCode}",
            'customer_notified' => true,
        ]);

        $this->activityLog->log(
            action: 'order.dispatched_steadfast',
            description: "Dispatched Order #{$order->order_number} via Steadfast Courier (Tracking: {$trackingCode})",
            subject: $order
        );

        // SMS Dispatch
        try {
            $this->smsService->sendOrderShippedSms($order);
        } catch (\Throwable $e) {
            Log::warning("Steadfast dispatch SMS failed: " . $e->getMessage());
        }

        return back()->with('success', "Order #{$order->order_number} successfully booked and dispatched with Steadfast Courier! Tracking: {$trackingCode}");
    }

    public function approveCancellation(Request $request, int $id): RedirectResponse
    {
        $order = Order::with('items.product')->findOrFail($id);

        // Restock inventory for items
        foreach ($order->items as $item) {
            if ($item->product && $item->product->track_inventory && !$item->product->is_unlimited_stock) {
                $prev = $item->product->stock_quantity ?? 0;
                $new = $prev + $item->quantity;
                $item->product->update(['stock_quantity' => $new]);

                InventoryLog::create([
                    'product_id' => $item->product->id,
                    'type' => StockMovementType::RETURN,
                    'quantity_delta' => $item->quantity,
                    'previous_quantity' => $prev,
                    'new_quantity' => $new,
                    'reference_type' => Order::class,
                    'reference_id' => $order->id,
                    'reason' => "Customer cancellation restock: Order #{$order->order_number}",
                ]);
            }
        }

        $order->status = OrderStatus::CANCELLED;
        $order->save();

        $curatorName = auth()->user()?->name ?? 'Staff';
        OrderTimeline::create([
            'order_id' => $order->id,
            'status' => $order->status->value,
            'actor_type' => 'staff',
            'note' => "Order cancellation approved by Curator [{$curatorName}]. Inventory has been safely restocked.",
            'customer_notified' => true,
        ]);

        $this->activityLog->log(
            action: 'order.cancellation_approved',
            description: "Curator approved cancellation for Order #{$order->order_number}",
            subject: $order
        );

        return back()->with('success', "Order #{$order->order_number} has been cancelled and items returned to stock.");
    }

    public function declineCancellation(Request $request, int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $curatorName = auth()->user()?->name ?? 'Staff';
        OrderTimeline::create([
            'order_id' => $order->id,
            'status' => $order->status->value,
            'actor_type' => 'staff',
            'note' => "Cancellation request declined by Curator [{$curatorName}]. Reason: {$validated['reason']}",
            'customer_notified' => true,
        ]);

        return back()->with('info', "Cancellation request declined for Order #{$order->order_number}.");
    }

    public function rejectPayment(Request $request, int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $order->payment_status = PaymentStatus::FAILED;
        $order->save();

        \App\Models\OrderTimeline::create([
            'order_id' => $order->id,
            'status' => $order->status->value,
            'actor_type' => 'staff',
            'note' => "Payment rejected by Curator [" . (auth()->user()?->name ?? 'Staff') . "]. Reason: {$validated['reason']}",
            'customer_notified' => true,
        ]);

        return back()->with('error', "Payment marked as invalid for Order #{$order->order_number}.");
    }
}
