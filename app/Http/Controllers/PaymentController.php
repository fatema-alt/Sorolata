<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\OrderTimeline;
use App\Services\Commerce\Payment\PaymentGatewayManager;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentGatewayManager $paymentManager
    ) {}

    /**
     * Display the bKash checkout page (simulated / test modal)
     */
    public function bkashCheckout(Request $request, string $order): View|RedirectResponse
    {
        $orderModel = Order::where('order_number', $order)->firstOrFail();

        $payableAmount = $orderModel->advance_delivery_charge > 0 && $orderModel->paid_amount == 0
            ? (float) $orderModel->advance_delivery_charge
            : (float) $orderModel->total_amount;

        $isAdvanceFee = $orderModel->advance_delivery_charge > 0 && $orderModel->paid_amount == 0;

        return view('payment.bkash_checkout', [
            'order' => $orderModel,
            'payableAmount' => $payableAmount,
            'isAdvanceFee' => $isAdvanceFee,
        ]);
    }

    /**
     * Handle bKash callback (live or simulated)
     */
    public function bkashCallback(Request $request, string $order): RedirectResponse
    {
        $orderModel = Order::where('order_number', $order)->firstOrFail();
        $gateway = $this->paymentManager->driver('bkash');
        $verification = $gateway->verifyPayment($request);

        if (!$verification->successful) {
            return redirect()->route('checkout.index')
                ->with('error', __('bKash Payment Failed: :error', ['error' => $verification->errorMessage ?? __('Transaction cancelled')]));
        }

        $paidAmount = $verification->amount > 0 ? $verification->amount : (float) $request->input('amount', $orderModel->total_amount);
        $isAdvancePayment = $orderModel->advance_delivery_charge > 0 && $paidAmount <= $orderModel->advance_delivery_charge;

        $newPaidAmount = (float) $orderModel->paid_amount + $paidAmount;
        $dueAmount = max(0.00, (float) $orderModel->total_amount - $newPaidAmount);

        $orderModel->update([
            'paid_amount' => $newPaidAmount,
            'due_amount' => $dueAmount,
            'transaction_id' => $verification->transactionId,
            'payment_status' => $dueAmount > 0 ? PaymentStatus::PARTIALLY_PAID : PaymentStatus::PAID,
            'status' => OrderStatus::CONFIRMED,
            'payment_details' => [
                'gateway' => 'bkash',
                'trx_id' => $verification->transactionId,
                'paid_amount' => $paidAmount,
                'paid_at' => now()->toIso8601String(),
                'type' => $isAdvancePayment ? 'advance_delivery_charge' : 'full_payment',
                'raw' => $verification->rawResponse,
            ],
        ]);

        OrderTimeline::create([
            'order_id' => $orderModel->id,
            'status' => $orderModel->status->value,
            'actor_type' => 'payment_gateway',
            'note' => $isAdvancePayment 
                ? "Advance delivery fee of ৳{$paidAmount} paid via bKash (TrxID: {$verification->transactionId}). Parcel queued for packaging. Remaining ৳{$dueAmount} due on delivery."
                : "Full order payment of ৳{$paidAmount} cleared via bKash (TrxID: {$verification->transactionId}).",
            'customer_notified' => true,
        ]);

        $message = $isAdvancePayment
            ? __('Advance delivery charge of ৳:paid successfully received! Your order #:order has been confirmed. The remaining ৳:due is payable on delivery.', [
                'paid' => number_format((float) $paidAmount, 2),
                'order' => $orderModel->order_number,
                'due' => number_format((float) $dueAmount, 2),
            ])
            : __('bKash payment successful! Order #:order has been confirmed.', [
                'order' => $orderModel->order_number,
            ]);

        return redirect()->route('track.order', ['order_number' => $orderModel->order_number])
            ->with('success', $message);
    }

    /**
     * Display SSLCommerz hosted checkout simulation
     */
    public function sslcommerzCheckout(Request $request, string $order): View|RedirectResponse
    {
        $orderModel = Order::where('order_number', $order)->firstOrFail();
        $tranId = $request->input('tran_id', 'SSLC_' . $orderModel->order_number . '_' . strtoupper(substr(uniqid(), -4)));

        $payableAmount = $orderModel->advance_delivery_charge > 0 && $orderModel->paid_amount == 0
            ? (float) $orderModel->advance_delivery_charge
            : (float) $orderModel->total_amount;

        $isAdvanceFee = $orderModel->advance_delivery_charge > 0 && $orderModel->paid_amount == 0;

        return view('payment.sslcommerz_checkout', [
            'order' => $orderModel,
            'tranId' => $tranId,
            'payableAmount' => $payableAmount,
            'isAdvanceFee' => $isAdvanceFee,
        ]);
    }

    /**
     * SSLCommerz Success callback
     */
    public function sslcommerzSuccess(Request $request): RedirectResponse
    {
        $tranId = (string) $request->input('tran_id', '');
        $orderNumber = (string) $request->input('value_a', '');

        $orderModel = null;
        if ($orderNumber) {
            $orderModel = Order::where('order_number', $orderNumber)->first();
        }
        if (!$orderModel && $tranId) {
            // Find by tran_id pattern SSLC_{order_number}_{rand}
            $parts = explode('_', $tranId);
            if (count($parts) >= 2) {
                $orderModel = Order::where('order_number', $parts[1])->first();
            }
        }

        if (!$orderModel) {
            return redirect()->route('checkout.index')->with('error', __('Order not found for this transaction.'));
        }

        $gateway = $this->paymentManager->driver('sslcommerz');
        $verification = $gateway->verifyPayment($request);

        if (!$verification->successful) {
            return redirect()->route('checkout.index')->with('error', __('SSLCommerz transaction validation failed.'));
        }

        $paidAmount = $verification->amount > 0 ? $verification->amount : (float) $request->input('amount', $orderModel->total_amount);
        $newPaidAmount = (float) $orderModel->paid_amount + $paidAmount;
        $dueAmount = max(0.00, (float) $orderModel->total_amount - $newPaidAmount);

        $orderModel->update([
            'paid_amount' => $newPaidAmount,
            'due_amount' => $dueAmount,
            'transaction_id' => $verification->transactionId,
            'payment_status' => $dueAmount > 0 ? PaymentStatus::PARTIALLY_PAID : PaymentStatus::PAID,
            'status' => OrderStatus::CONFIRMED,
            'payment_details' => [
                'gateway' => 'sslcommerz',
                'tran_id' => $verification->transactionId,
                'val_id' => $request->input('val_id'),
                'card_type' => $request->input('card_type', 'Internet Banking/Cards'),
                'paid_amount' => $paidAmount,
                'paid_at' => now()->toIso8601String(),
                'raw' => $verification->rawResponse,
            ],
        ]);

        OrderTimeline::create([
            'order_id' => $orderModel->id,
            'status' => $orderModel->status->value,
            'actor_type' => 'payment_gateway',
            'note' => "Payment of ৳{$paidAmount} completed via SSLCommerz ({$request->input('card_type', 'Card/MFS')}). Transaction ID: {$verification->transactionId}.",
            'customer_notified' => true,
        ]);

        return redirect()->route('track.order', ['order_number' => $orderModel->order_number])
            ->with('success', __('SSLCommerz payment successful! Order #:order has been confirmed.', ['order' => $orderModel->order_number]));
    }

    /**
     * SSLCommerz Fail callback
     */
    public function sslcommerzFail(Request $request): RedirectResponse
    {
        Log::warning('SSLCommerz payment failed', $request->all());
        return redirect()->route('checkout.index')
            ->with('error', __('SSLCommerz payment failed. Please try again or select bKash / Cash on Delivery.'));
    }

    /**
     * SSLCommerz Cancel callback
     */
    public function sslcommerzCancel(Request $request): RedirectResponse
    {
        return redirect()->route('checkout.index')
            ->with('info', __('Payment was cancelled. You may choose another payment method.'));
    }

    /**
     * Display Manual Direct Payment Instruction Page (bKash/Nagad/BRAC/DBBL/COD)
     */
    public function manualInstruction(Request $request, string $order): View
    {
        $orderModel = Order::with('items')->where('order_number', $order)->firstOrFail();
        $settings = \App\Models\AdminSetting::all()->pluck('value', 'key')->toArray();

        $isCod = in_array(strtolower($orderModel->payment_gateway?->value ?? ''), ['cash_on_delivery', 'cod'], true);
        $isAdvanceFee = $orderModel->advance_delivery_charge > 0 && $orderModel->paid_amount == 0;

        $payableAmount = ($isCod && $isAdvanceFee)
            ? (float) $orderModel->advance_delivery_charge
            : (float) $orderModel->total_amount;

        return view('payment.manual_instruction', [
            'order' => $orderModel,
            'settings' => $settings,
            'payableAmount' => $payableAmount,
            'isCod' => $isCod,
            'isAdvanceFee' => $isAdvanceFee,
        ]);
    }

    /**
     * Handle Customer Submission of Transaction ID (TrxID) & Sender Information
     */
    public function submitManualPayment(Request $request, string $order): RedirectResponse
    {
        $orderModel = Order::where('order_number', $order)->firstOrFail();

        $validated = $request->validate([
            'transaction_id' => 'required|string|max:100',
            'sender_number' => 'required|string|max:100',
            'payment_method' => 'required|string|max:50',
            'customer_notes' => 'nullable|string|max:500',
        ]);

        $details = $orderModel->payment_details ?? [];
        $details['sender_number'] = $validated['sender_number'];
        $details['submitted_trx_id'] = $validated['transaction_id'];
        $details['method_used'] = $validated['payment_method'];
        $details['customer_notes'] = $validated['customer_notes'] ?? null;
        $details['submitted_at'] = now()->toIso8601String();

        $methodKey = strtolower(trim($validated['payment_method']));
        $gateway = match ($methodKey) {
            'bkash' => PaymentGateway::BKASH,
            'nagad' => PaymentGateway::NAGAD,
            'brac', 'brac_bank' => PaymentGateway::BRAC_BANK,
            'dbbl', 'rocket' => PaymentGateway::DBBL,
            'cash_on_delivery', 'cod' => PaymentGateway::COD,
            default => PaymentGateway::tryFrom($methodKey) ?? PaymentGateway::COD,
        };

        $isCod = $request->input('payment_type') === 'cod' || $gateway === PaymentGateway::COD;
        $advanceCharge = $isCod ? (float) $orderModel->shipping_fee : 0.00;

        $orderModel->update([
            'payment_gateway' => $gateway,
            'advance_delivery_charge' => $advanceCharge,
            'transaction_id' => $validated['transaction_id'],
            'payment_details' => $details,
        ]);

        $methodLabel = match ($validated['payment_method']) {
            'bkash' => 'bKash Mobile Banking',
            'nagad' => 'Nagad Mobile Banking',
            'brac_bank' => 'BRAC Bank Transfer',
            'dbbl' => 'Dutch-Bangla Bank',
            default => ucfirst($validated['payment_method']),
        };

        OrderTimeline::create([
            'order_id' => $orderModel->id,
            'status' => $orderModel->status->value,
            'actor_type' => 'customer',
            'note' => "Customer submitted payment details via {$methodLabel}. Sender: {$validated['sender_number']}, TrxID: {$validated['transaction_id']}. Awaiting verification by accounts team.",
            'customer_notified' => true,
        ]);

        // Dispatch Centralized Admin Notification
        try {
            app(\App\Services\Admin\AdminNotificationService::class)->notifyPaymentSubmitted(
                $orderModel,
                $validated['transaction_id'],
                $methodLabel
            );
        } catch (\Throwable $e) {
            Log::warning("Payment notification trigger failed: " . $e->getMessage());
        }

        return redirect()->route('track.order', ['order_number' => $orderModel->order_number])
            ->with('success', __('Your payment transaction ID (#:trx) has been submitted successfully! Our team will verify and confirm your order shortly.', ['trx' => $validated['transaction_id']]));
    }

    /**
     * SSLCommerz IPN (Instant Payment Notification) listener
     */
    public function sslcommerzIpn(Request $request): JsonResponse
    {
        Log::info('SSLCommerz IPN received', $request->all());
        return response()->json(['status' => 'OK']);
    }
}
