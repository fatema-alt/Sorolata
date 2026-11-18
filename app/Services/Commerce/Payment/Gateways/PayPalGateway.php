<?php

declare(strict_types=1);

namespace App\Services\Commerce\Payment\Gateways;

use App\Contracts\PaymentGatewayContract;
use App\DTO\PaymentInitiationDTO;
use App\DTO\PaymentVerificationDTO;
use App\Models\Order;
use Illuminate\Http\Request;

class PayPalGateway implements PaymentGatewayContract
{
    public function getIdentifier(): string
    {
        return 'paypal';
    }

    public function getDisplayName(): string
    {
        return 'PayPal Global Express';
    }

    public function isAvailable(): bool
    {
        return true;
    }

    public function initiatePayment(Order $order, array $payload = []): PaymentInitiationDTO
    {
        return PaymentInitiationDTO::redirect(
            url: "https://www.sandbox.paypal.com/checkoutnow?token=EC-" . strtoupper(uniqid()),
            gateway: $this->getIdentifier(),
            txId: 'PAYPAL_' . strtoupper(uniqid())
        );
    }

    public function verifyPayment(Request $request): PaymentVerificationDTO
    {
        return PaymentVerificationDTO::success(
            txId: 'PAYPAL_' . strtoupper(uniqid()),
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
