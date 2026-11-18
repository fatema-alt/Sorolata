<?php

declare(strict_types=1);

namespace App\Enums;

enum DiscountType: string
{
    case PERCENTAGE = 'percentage';
    case FIXED_AMOUNT = 'fixed_amount';
    case BUY_X_GET_Y = 'buy_x_get_y';
    case FREE_SHIPPING = 'free_shipping';
    case BUNDLE = 'bundle';

    public function label(): string
    {
        return match ($this) {
            self::PERCENTAGE => __('Percentage Discount'),
            self::FIXED_AMOUNT => __('Fixed Amount Discount'),
            self::BUY_X_GET_Y => __('Buy X Get Y'),
            self::FREE_SHIPPING => __('Complimentary Shipping'),
            self::BUNDLE => __('Curated Bundle Promotion'),
        };
    }
}
