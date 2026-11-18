<?php

declare(strict_types=1);

namespace App\Enums;

enum StockMovementType: string
{
    case PURCHASE = 'purchase';
    case ADJUSTMENT = 'adjustment';
    case SALE = 'sale';
    case RETURN = 'return';
    case DAMAGE = 'damage';
    case RESERVATION = 'reservation';

    public function isAddition(): bool
    {
        return in_array($this, [self::PURCHASE, self::RETURN], true);
    }

    public function isDeduction(): bool
    {
        return in_array($this, [self::SALE, self::DAMAGE, self::RESERVATION], true);
    }
}
