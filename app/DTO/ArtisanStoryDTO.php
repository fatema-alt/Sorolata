<?php

declare(strict_types=1);

namespace App\DTO;

use App\Enums\ArtisanCraftType;

final readonly class ArtisanStoryDTO
{
    /**
     * @param string[] $awards
     * @param string[] $galleryImages
     */
    public function __construct(
        public string $artisanId,
        public string $fullName,
        public string $atelierName,
        public ArtisanCraftType $craftType,
        public int $yearsOfMastery,
        public string $biography,
        public string $districtName,
        public string $portraitUrl,
        public bool $isVerified,
        public ?string $quote = null,
        public array $awards = [],
        public array $galleryImages = []
    ) {}
}
