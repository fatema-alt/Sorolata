<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentStatus: string
{
    case UNPAID = 'unpaid';
    case AUTHORIZED = 'authorized';
    case PAID = 'paid';
    case PARTIALLY_PAID = 'partially_paid';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';
    case CHARGEBACK = 'chargeback';

    public function isSuccessful(): bool
    {
        return in_array($this, [self::PAID, self::PARTIALLY_PAID], true);
    }

    public function label(): string
    {
        return $this->displayName();
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PAID => 'bg-emerald-950/80 text-emerald-300 border-emerald-800',
            self::PARTIALLY_PAID => 'bg-amber-950/80 text-amber-300 border-amber-800',
            self::AUTHORIZED => 'bg-blue-950/80 text-blue-300 border-blue-800',
            self::UNPAID => 'bg-gray-800 text-gray-300 border-gray-700',
            self::FAILED => 'bg-rose-950/80 text-rose-300 border-rose-800',
            self::REFUNDED, self::CHARGEBACK => 'bg-purple-950/80 text-purple-300 border-purple-800',
        };
    }

    public function displayName(): string
    {
        return match ($this) {
            self::UNPAID => __('Unpaid / Pending'),
            self::AUTHORIZED => __('Payment Authorized'),
            self::PAID => __('Paid in Full'),
            self::PARTIALLY_PAID => __('Advance Delivery Fee Paid (COD Due)'),
            self::FAILED => __('Payment Failed'),
            self::REFUNDED => __('Refunded'),
            self::CHARGEBACK => __('Chargeback Initiated'),
        };
    }
}
