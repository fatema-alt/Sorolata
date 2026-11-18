<?php

declare(strict_types=1);

namespace App\Services\Commerce\Payment\Gateways;

use App\Contracts\PaymentGatewayContract;
use App\DTO\PaymentInitiationDTO;
use App\DTO\PaymentVerificationDTO;
use App\Models\Order;
use Illuminate\Http\Request;

class StripeGateway implements PaymentGatewayContract
{
    public function getIdentifier(): string
    {
        return 'stripe';
    }

    public function getDisplayName(): string
    {
        return 'International Cards (Stripe Global)';
    }

    public function isAvailable(): bool
    {
        return true;
    }

    public function initiatePayment(Order $order, array $payload = []): PaymentInitiationDTO
    {
        $sessionUrl = "/payment/stripe/checkout-session?order={$order->order_number}";

        return PaymentInitiationDTO::redirect(
            url: $sessionUrl,
            gateway: $this->getIdentifier(),
            txId: 'cs_test_' . strtoupper(uniqid())
        );
    }

    public function verifyPayment(Request $request): PaymentVerificationDTO
    {
        $sessionId = (string) $request->input('session_id', 'cs_test_ok');

        return PaymentVerificationDTO::success(
            txId: $sessionId,
            amount: (float) $request->input('amount', 0),
            currency: 'USD',
            gateway: $this->getIdentifier(),
            raw: $request->all()
        );
    }

    public function processRefund(Order $order, float $amount, string $reason): bool
    {
        return true;
    }
}
