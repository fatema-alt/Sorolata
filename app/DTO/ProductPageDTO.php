<?php

declare(strict_types=1);

namespace App\DTO;

use App\Enums\ThemeMode;

final readonly class ProductPageDTO
{
    /**
     * @param LayoutBlockDTO[] $blocks
     * @param array<string, mixed> $themeTokens
     * @param array<string, mixed> $seoMeta
     */
    public function __construct(
        public string $productId,
        public string $title,
        public string $slug,
        public float $price,
        public ?float $compareAtPrice,
        public string $currency,
        public ThemeMode $themeMode,
        public array $blocks,
        public array $themeTokens = [],
        public array $seoMeta = []
    ) {}
}
