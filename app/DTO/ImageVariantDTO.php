<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class ImageVariantDTO
{
    /**
     * @param array<string, string> $avifUrls  e.g. ['thumbnail' => '...', 'large' => '...']
     * @param array<string, string> $webpUrls
     * @param array<string, string> $originalUrls
     */
    public function __construct(
        public string $assetId,
        public string $altText,
        public int $originalWidth,
        public int $originalHeight,
        public string $aspectRatio,
        public ?string $blurHash,
        public ?string $tinyBase64Placeholder,
        public array $avifUrls = [],
        public array $webpUrls = [],
        public array $originalUrls = []
    ) {}
}
