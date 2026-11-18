<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class PaymentVerificationDTO
{
    public function __construct(
        public bool $successful,
        public string $transactionId,
        public float $amount,
        public string $currency,
        public string $gateway,
        public array $rawResponse = [],
        public ?string $errorMessage = null
    ) {}

    public static function success(string $txId, float $amount, string $currency, string $gateway, array $raw = []): self
    {
        return new self(
            successful: true,
            transactionId: $txId,
            amount: $amount,
            currency: $currency,
            gateway: $gateway,
            rawResponse: $raw
        );
    }

    public static function failure(string $errorMessage, string $gateway, array $raw = []): self
    {
        return new self(
            successful: false,
            transactionId: '',
            amount: 0.0,
            currency: 'BDT',
            gateway: $gateway,
            rawResponse: $raw,
            errorMessage: $errorMessage
        );
    }
}
