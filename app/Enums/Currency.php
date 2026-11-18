<?php

declare(strict_types=1);

namespace App\Enums;

enum Currency: string
{
    case BDT = 'BDT';
    case USD = 'USD';
    case EUR = 'EUR';
    case GBP = 'GBP';
    case CAD = 'CAD';
    case AUD = 'AUD';

    public function symbol(): string
    {
        return match ($this) {
            self::BDT => '৳',
            self::USD => '$',
            self::EUR => '€',
            self::GBP => '£',
            self::CAD => 'CA$',
            self::AUD => 'AU$',
        };
    }

    public function format(float|int|string $amount): string
    {
        $num = (float) $amount;
        return match ($this) {
            self::BDT => '৳ ' . number_format($num, 0),
            self::USD => '$' . number_format($num, 2),
            self::EUR => '€' . number_format($num, 2),
            self::GBP => '£' . number_format($num, 2),
            self::CAD => 'CA$' . number_format($num, 2),
            self::AUD => 'AU$' . number_format($num, 2),
        };
    }
}
