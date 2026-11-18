<?php

declare(strict_types=1);

namespace App\Services\Commerce\Payment\Gateways;

use App\Contracts\PaymentGatewayContract;
use App\DTO\PaymentInitiationDTO;
use App\DTO\PaymentVerificationDTO;
use App\Models\Order;
use Illuminate\Http\Request;

class ManualPaymentGateway implements PaymentGatewayContract
{
    public function __construct(
        protected string $identifier = 'bank_transfer',
        protected string $displayName = 'Manual Direct Payment'
    ) {}

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function isAvailable(): bool
    {
        return true;
    }

    public function initiatePayment(Order $order, array $payload = []): PaymentInitiationDTO
    {
        return PaymentInitiationDTO::redirect(
            url: route('payment.manual.instruction', ['order' => $order->order_number]),
            gateway: $this->getIdentifier(),
            txId: 'MANUAL_' . $order->order_number
        );
    }

    public function verifyPayment(Request $request): PaymentVerificationDTO
    {
        $trxId = (string) $request->input('trxID', $request->input('transaction_id', 'TRX_' . strtoupper(uniqid())));
        $amount = (float) $request->input('amount', 0);

        return PaymentVerificationDTO::success(
            txId: $trxId,
            amount: $amount,
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
