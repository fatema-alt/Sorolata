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
            self::PHYSICAL => 'Physical Heritage Artifact',
            self::DIGITAL => 'Digital Archival Asset',
            self::GIFT_CARD => 'Collector Gift Card',
            self::BUNDLE => 'Curated Heritage Set',
            self::SERVICE => 'Bespoke Atelier Commission',
        };
    }
}
