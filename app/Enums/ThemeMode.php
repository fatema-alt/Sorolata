<?php

declare(strict_types=1);

namespace App\Enums;

enum ThemeMode: string
{
    case WARM_HERITAGE = 'warm_heritage';
    case NATURAL_LINEN = 'natural_linen';
    case DARK_EDITORIAL = 'dark_editorial';
    case MINIMAL_IVORY = 'minimal_ivory';
    case CLAY_TERRACOTTA = 'clay_terracotta';

    public function cssClass(): string
    {
        return match ($this) {
            self::WARM_HERITAGE => 'theme-warm-heritage bg-[#FBF9F5] text-[#241F1E]',
            self::NATURAL_LINEN => 'theme-natural-linen bg-[#F5F2EB] text-[#2C2825]',
            self::DARK_EDITORIAL => 'theme-dark-editorial bg-[#1A1817] text-[#EDE8DF]',
            self::MINIMAL_IVORY => 'theme-minimal-ivory bg-[#FAFAFA] text-[#111111]',
            self::CLAY_TERRACOTTA => 'theme-clay-terracotta bg-[#F6F1EC] text-[#261E1B]',
        };
    }
}
