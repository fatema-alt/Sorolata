<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\PaymentInitiationDTO;
use App\DTO\PaymentVerificationDTO;
use App\Models\Order;
use Illuminate\Http\Request;

interface PaymentGatewayContract
{
    public function getIdentifier(): string;

    public function getDisplayName(): string;

    public function isAvailable(): bool;

    public function initiatePayment(Order $order, array $payload = []): PaymentInitiationDTO;

    public function verifyPayment(Request $request): PaymentVerificationDTO;

    public function processRefund(Order $order, float $amount, string $reason): bool;
}
