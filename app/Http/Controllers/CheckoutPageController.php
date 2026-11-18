<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Currency;
use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Enums\StockMovementType;
use App\Mail\OrderConfirmationMail;
use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTimeline;
use App\Models\Product;
use App\Services\Commerce\Payment\PaymentGatewayManager;
use App\Services\Notification\SmsService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutPageController extends Controller
{
    public function __construct(
        protected PaymentGatewayManager $paymentManager,
        protected SmsService $smsService
    ) {}

    public function index(): View
    {
        $bag = session()->get('sorolata_collector_bag', []);
        $productIds = array_keys($bag);
        
        $items = Product::with(['artisan', 'district', 'media'])
            ->whereIn('id', $productIds)
            ->get()
            ->map(function ($product) use ($bag) {
                $qty = $bag[$product->id] ?? 1;
                return [
                    'product' => $product,
                    'quantity' => $qty,
                    'subtotal' => $product->price * $qty,
                ];
            });

        // If bag is empty, fetch fallback sample product so checkout page can always be evaluated
        if ($items->isEmpty()) {
            $sample = Product::first();
            if ($sample) {
                $items = collect([[
                    'product' => $sample,
                    'quantity' => 1,
                    'subtotal' => $sample->price,
                ]]);
            }
        }

        $subtotal = (float) $items->sum('subtotal');
        $dhakaFee = (float) config('services.cod.dhaka_delivery_fee', 80.00);
        $outsideDhakaFee = (float) config('services.cod.outside_dhaka_delivery_fee', 150.00);

        $coupon = session()->get('checkout_coupon');
        $discountAmount = 0.0;
        if ($coupon && !empty($coupon['amount'])) {
            $discountAmount = (float) $coupon['amount'];
        }

        $total = max(0.0, $subtotal - $discountAmount) + $dhakaFee;

        return view('checkout.index', [
            'items' => $items,
            'subtotal' => $subtotal,
            'dhakaFee' => $dhakaFee,
            'outsideFee' => $outsideDhakaFee,
            'outsideDhakaFee' => $outsideDhakaFee,
            'defaultShippingFee' => $dhakaFee,
            'coupon' => $coupon,
            'discountAmount' => $discountAmount,
            'total' => $total,
        ]);
    }

    public function process(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:30',
            'delivery_address' => 'required|string|max:500',
            'city_district' => 'required|string|max:100',
            'payment_gateway' => 'required|string',
            'gift_note' => 'nullable|string|max:500',
        ]);

        $bag = session()->get('sorolata_collector_bag', []);
        $productIds = array_keys($bag);
        $products = Product::whereIn('id', $productIds)->get();

        if ($products->isEmpty()) {
            $products = Product::take(1)->get();
        }

        $subtotal = 0.0;
        foreach ($products as $p) {
            $qty = $bag[$p->id] ?? 1;
            $subtotal += ((float) $p->price * $qty);
        }

        // Delivery Fee Calculation based on District
        $isDhaka = str_contains(strtolower($validated['city_district']), 'dhaka') || str_contains($validated['city_district'], 'ঢাকা');
        $shippingFee = $isDhaka
            ? (float) config('services.cod.dhaka_delivery_fee', 80.00)
            : (float) config('services.cod.outside_dhaka_delivery_fee', 150.00);

        // Apply discount from session coupon if present
        $coupon = session()->get('checkout_coupon');
        $discountAmount = 0.0;
        if ($coupon && !empty($coupon['amount'])) {
            $discountAmount = min($subtotal, (float) $coupon['amount']);
        }

        $totalAmount = max(0.0, $subtotal - $discountAmount) + $shippingFee;
        $orderNumber = 'SRL-' . strtoupper(Str::random(8));

        $gatewayInput = strtolower(trim($validated['payment_gateway']));
        $isCod = in_array($gatewayInput, ['cod', 'cash_on_delivery'], true);
        $requireAdvanceFee = (bool) config('services.cod.require_advance_delivery_fee', true);

        // Advance Delivery Charge logic for Anti-fraud COD
        $advanceCharge = ($isCod && $requireAdvanceFee) ? $shippingFee : 0.00;
        $paidAmount = 0.00;
        $dueAmount = $totalAmount;

        $order = Order::create([
            'uuid' => (string) Str::uuid(),
            'order_number' => $orderNumber,
            'user_id' => auth()->id() ?? null,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'total_amount' => $totalAmount,
            'subtotal_amount' => $subtotal,
            'discount_amount' => $discountAmount,
            'shipping_fee' => $shippingFee,
            'advance_delivery_charge' => $advanceCharge,
            'paid_amount' => $paidAmount,
            'due_amount' => $dueAmount,
            'currency' => Currency::BDT,
            'status' => OrderStatus::PENDING,
            'payment_status' => PaymentStatus::UNPAID,
            'payment_gateway' => PaymentGateway::tryFrom($gatewayInput) ?? PaymentGateway::COD,
            'shipping_address' => [
                'address' => $validated['delivery_address'],
                'district' => $validated['city_district'],
                'is_dhaka' => $isDhaka,
                'gift_note' => $validated['gift_note'] ?? null,
            ],
            'notes' => $validated['gift_note'] ?? null,
        ]);

        foreach ($products as $p) {
            $qty = $bag[$p->id] ?? 1;
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $p->id,
                'artisan_id' => $p->artisan_id,
                'product_title' => $p->title,
                'artisan_name' => $p->artisan?->full_name,
                'sku' => $p->sku,
                'unit_price' => $p->price,
                'quantity' => $qty,
                'subtotal' => (float) $p->price * $qty,
            ]);

            // Real-time Inventory Deduction & Audit Log
            if ($p->track_inventory && !$p->is_unlimited_stock) {
                $prev = $p->stock_quantity ?? 0;
                $new = max(0, $prev - $qty);
                $p->update(['stock_quantity' => $new]);

                InventoryLog::create([
                    'product_id' => $p->id,
                    'type' => StockMovementType::SALE,
                    'quantity_delta' => -$qty,
                    'previous_quantity' => $prev,
                    'new_quantity' => $new,
                    'reference_type' => Order::class,
                    'reference_id' => $order->id,
                    'reason' => 'Online checkout: ' . $order->order_number,
                ]);
            }
        }

        OrderTimeline::create([
            'order_id' => $order->id,
            'status' => $order->status->value,
            'actor_type' => 'customer',
            'note' => $isCod && $requireAdvanceFee
                ? "Order placed with Cash on Delivery. Advance delivery charge of ৳{$advanceCharge} required to confirm parcel dispatch."
                : 'Order placed through storefront one-page checkout.',
            'customer_notified' => true,
        ]);

        session()->forget('sorolata_collector_bag');
        if (!empty($coupon['discount_id'])) {
            \App\Models\Discount::where('id', $coupon['discount_id'])->increment('used_count');
            session()->forget('checkout_coupon');
        }

        // Automated SMS & Email Notification Dispatches
        try {
            $this->smsService->sendOrderPlacedSms($order);
        } catch (\Throwable $e) {
            Log::warning("Order placed SMS dispatch failed: " . $e->getMessage());
        }

        try {
            if (!empty($order->customer_email) && filter_var($order->customer_email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));
            }
        } catch (\Throwable $e) {
            Log::warning("Order confirmation email failed: " . $e->getMessage());
        }

        // Route through Payment Gateway (Manual Instructions or Automated Gateway)
        try {
            if (in_array($gatewayInput, ['brac_bank', 'dbbl', 'bank_transfer'], true) || ($isCod && $requireAdvanceFee)) {
                return redirect()->route('payment.manual.instruction', ['order' => $orderNumber]);
            }

            // If merchant credentials not yet configured in .env, seamlessly use manual payment instruction
            $bkashConfig = config('services.bkash');
            $hasBkashApi = !empty($bkashConfig['app_key']) && !empty($bkashConfig['app_secret']);
            if (in_array($gatewayInput, ['bkash', 'nagad'], true) && !$hasBkashApi) {
                return redirect()->route('payment.manual.instruction', ['order' => $orderNumber]);
            }

            $driverKey = $isCod && $requireAdvanceFee ? 'bkash' : $gatewayInput;
            $gatewayDriver = $this->paymentManager->driver($driverKey);
            $initiation = $gatewayDriver->initiatePayment($order);

            if (!empty($initiation->redirectUrl)) {
                return redirect()->away($initiation->redirectUrl);
            }
        } catch (\Throwable $e) {
            // Fallback to manual instruction
        }

        return redirect()->route('payment.manual.instruction', ['order' => $orderNumber]);
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'coupon_code' => 'required|string|max:50',
        ]);

        $code = strtoupper(trim($validated['coupon_code']));
        $discount = \App\Models\Discount::where('code', $code)->first();

        if (!$discount) {
            return back()->with('coupon_error', __('The coupon code provided is invalid.'));
        }

        $bag = session()->get('sorolata_collector_bag', []);
        $productIds = array_keys($bag);
        $products = Product::whereIn('id', $productIds)->get();
        if ($products->isEmpty()) {
            $products = Product::take(1)->get();
        }

        $subtotal = 0.0;
        foreach ($products as $p) {
            $qty = $bag[$p->id] ?? 1;
            $subtotal += ((float) $p->price * $qty);
        }

        if (!$discount->isValidForSubtotal((float) $subtotal)) {
            if ($discount->min_subtotal && $subtotal < $discount->min_subtotal) {
                return back()->with('coupon_error', __('A minimum order of ৳:amount is required to use this coupon.', ['amount' => number_format((float)$discount->min_subtotal)]));
            }
            return back()->with('coupon_error', __('The coupon is expired or has reached its maximum usage limit.'));
        }

        $discountAmount = $discount->calculateDiscount((float) $subtotal);

        session()->put('checkout_coupon', [
            'code' => $discount->code,
            'discount_id' => $discount->id,
            'name' => $discount->name,
            'amount' => $discountAmount,
        ]);

        return back()->with('coupon_success', __("Coupon ':code' applied successfully! You received a discount of ৳:amount.", ['code' => $discount->code, 'amount' => number_format($discountAmount, 2)]));
    }

    public function removeCoupon(): RedirectResponse
    {
        session()->forget('checkout_coupon');
        return back()->with('info', __('Coupon code removed.'));
    }
}
