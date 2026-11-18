<?php

declare(strict_types=1);

namespace App\Enums;

enum ProductType: string
{
    case PHYSICAL = 'physical';
    case DIGITAL = 'digital';
    case GIFT_CARD = 'gift_card';
    case BUNDLE = 'bundle';
    case SERVICE = 'service';

    public function label(): string
    {
        return match ($this) {
            self::PHYSICAL => __('Physical Heritage Artifact'),
            self::DIGITAL => __('Digital Archival Asset'),
            self::GIFT_CARD => __('Collector Gift Card'),
            self::BUNDLE => __('Curated Heritage Set'),
            self::SERVICE => __('Bespoke Atelier Commission'),
        };
    }
}
