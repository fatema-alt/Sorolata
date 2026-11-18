<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class PaymentInitiationDTO
{
    public function __construct(
        public bool $success,
        public ?string $redirectUrl = null,
        public ?string $transactionId = null,
        public string $gateway = 'cash_on_delivery',
        public ?string $instructions = null,
        public array $metadata = []
    ) {}

    public static function redirect(string $url, string $gateway, ?string $txId = null, array $meta = []): self
    {
        return new self(
            success: true,
            redirectUrl: $url,
            transactionId: $txId,
            gateway: $gateway,
            metadata: $meta
        );
    }

    public static function direct(string $gateway, ?string $instructions = null, array $meta = []): self
    {
        return new self(
            success: true,
            redirectUrl: null,
            transactionId: 'TXN_' . strtoupper(uniqid()),
            gateway: $gateway,
            instructions: $instructions,
            metadata: $meta
        );
    }
}
