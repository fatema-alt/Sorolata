<?php

declare(strict_types=1);

namespace App\Enums;

enum ArtisanCraftType: string
{
    case JAMDANI_WEAVING = 'jamdani_weaving';
    case MUSLIN_HANDSPUN = 'muslin_handspun';
    case NAKSHI_KANTHA = 'nakshi_kantha';
    case CLAY_POTTERY = 'clay_pottery';
    case BRASS_BELL_METAL = 'brass_bell_metal';
    case SHATRANJI_FLATWEAVE = 'shatranji_flatweave';
    case CANE_BAMBOO = 'cane_bamboo';
    case RAJSHAHI_SILK = 'rajshahi_silk';
    case WOOD_CARVING = 'wood_carving';
    case LEATHER_CRAFT = 'leather_craft';

    public function title(): string
    {
        return match ($this) {
            self::JAMDANI_WEAVING => __('Heritage Jamdani Weaving'),
            self::MUSLIN_HANDSPUN => __('Fine Handspun Muslin'),
            self::NAKSHI_KANTHA => __('Hand-Embroidered Nakshi Kantha'),
            self::CLAY_POTTERY => __('Terracotta & Glazed Pottery'),
            self::BRASS_BELL_METAL => __('Hand-Forged Brass & Bell Metal'),
            self::SHATRANJI_FLATWEAVE => __('Heritage Shatranji Weaving'),
            self::CANE_BAMBOO => __('Hand-Split Cane & Bamboo'),
            self::RAJSHAHI_SILK => __('Mulberry Sericulture & Silk'),
            self::WOOD_CARVING => __('Architectural Woodcarving'),
            self::LEATHER_CRAFT => __('Vegetable-Tanned Artisan Leather'),
        };
    }

    public function label(): string
    {
        return $this->title();
    }
}
