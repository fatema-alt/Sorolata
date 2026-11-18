<?php

declare(strict_types=1);

namespace App\Services\Commerce\Payment\Gateways;

use App\Contracts\PaymentGatewayContract;
use App\DTO\PaymentInitiationDTO;
use App\DTO\PaymentVerificationDTO;
use App\Models\Order;
use Illuminate\Http\Request;

class RocketGateway implements PaymentGatewayContract
{
    public function getIdentifier(): string
    {
        return 'rocket';
    }

    public function getDisplayName(): string
    {
        return 'DBBL Rocket';
    }

    public function isAvailable(): bool
    {
        return true;
    }

    public function initiatePayment(Order $order, array $payload = []): PaymentInitiationDTO
    {
        return PaymentInitiationDTO::redirect(
            url: "/payment/rocket/callback?orderID={$order->order_number}",
            gateway: $this->getIdentifier(),
            txId: 'RKT_' . strtoupper(uniqid())
        );
    }

    public function verifyPayment(Request $request): PaymentVerificationDTO
    {
        return PaymentVerificationDTO::success(
            txId: 'RKT_' . strtoupper(uniqid()),
            amount: (float) $request->input('amount', 0),
            currency: 'BDT',
            gateway: $this->getIdentifier(),
            raw: $request->all()
        );
    }

    public function processRefund(Order $order, float $amount, string $reason): bool
    {
        return true;
    }
}
