<?php

declare(strict_types=1);

namespace App\Services\Commerce\Payment\Gateways;

use App\Contracts\PaymentGatewayContract;
use App\DTO\PaymentInitiationDTO;
use App\DTO\PaymentVerificationDTO;
use App\Models\Order;
use Illuminate\Http\Request;

class CashOnDeliveryGateway implements PaymentGatewayContract
{
    public function getIdentifier(): string
    {
        return 'cash_on_delivery';
    }

    public function getDisplayName(): string
    {
        return 'Cash On Delivery (Verified White-Glove Handover)';
    }

    public function isAvailable(): bool
    {
        return true;
    }

    public function initiatePayment(Order $order, array $payload = []): PaymentInitiationDTO
    {
        $advanceCharge = (float) ($order->advance_delivery_charge ?? 0);

        if ($advanceCharge > 0 && ((float) ($order->paid_amount ?? 0)) < $advanceCharge) {
            // Forward to bKash gateway to clear the advance delivery charge
            return PaymentInitiationDTO::redirect(
                url: route('payment.bkash.checkout', ['order' => $order->order_number, 'type' => 'advance_delivery_charge']),
                gateway: $this->getIdentifier(),
                txId: 'COD_ADV_' . $order->order_number
            );
        }

        return PaymentInitiationDTO::direct(
            gateway: $this->getIdentifier(),
            instructions: 'Cash on delivery confirmed. Please pay the remaining amount of ৳' . number_format((float) ($order->due_amount ?: $order->total_amount), 2) . ' upon handover.',
            meta: ['order_number' => $order->order_number]
        );
    }

    public function verifyPayment(Request $request): PaymentVerificationDTO
    {
        return PaymentVerificationDTO::success(
            txId: 'COD_' . strtoupper(uniqid()),
            amount: (float) $request->input('amount', 0),
            currency: 'BDT',
            gateway: $this->getIdentifier()
        );
    }

    public function processRefund(Order $order, float $amount, string $reason): bool
    {
        return true;
    }
}
