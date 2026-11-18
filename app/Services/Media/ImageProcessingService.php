<?php

declare(strict_types=1);

namespace App\Services\Media;

use App\Contracts\ImageProcessorContract;
use App\DTO\ImageVariantDTO;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ImageProcessingService implements ImageProcessorContract
{
    /**
     * @var array<string, int>
     */
    protected array $variantSizes = [
        'thumbnail' => 160,
        'small' => 480,
        'medium' => 960,
        'large' => 1920,
    ];

    public function processUpload(UploadedFile $file, string $disk = 'public', string $directory = 'media'): ImageVariantDTO
    {
        $hashName = Str::random(32);
        $webpUrls = [];
        $avifUrls = [];
        $originalUrls = [];

        $storageDisk = Storage::disk($disk);
        $baseOriginalPath = "{$directory}/{$hashName}_original." . $file->getClientOriginalExtension();
        $storageDisk->putFileAs($directory, $file, "{$hashName}_original." . $file->getClientOriginalExtension());
        $originalUrls['original'] = $storageDisk->url($baseOriginalPath);

        $width = 1200;
        $height = 1200;

        try {
            $imageInstance = Image::read($file->getRealPath());
            $width = $imageInstance->width();
            $height = $imageInstance->height();

            foreach ($this->variantSizes as $variant => $targetWidth) {
                if ($width > $targetWidth) {
                    $resized = clone $imageInstance;
                    $resized->scale(width: $targetWidth);

                    // WebP variant
                    $webpPath = "{$directory}/{$hashName}_{$variant}.webp";
                    $storageDisk->put($webpPath, (string) $resized->toWebp(quality: 82));
                    $webpUrls[$variant] = $storageDisk->url($webpPath);

                    // Try AVIF if driver supports it
                    try {
                        $avifPath = "{$directory}/{$hashName}_{$variant}.avif";
                        $storageDisk->put($avifPath, (string) $resized->toAvif(quality: 75));
                        $avifUrls[$variant] = $storageDisk->url($avifPath);
                    } catch (\Throwable) {
                        // AVIF not compiled in local GD, webp suffices
                        $avifUrls[$variant] = $webpUrls[$variant];
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Image manipulation failed, using original file: {$e->getMessage()}");
        }

        $blurPlaceholder = $this->generateBlurPlaceholder($file->getRealPath());
        $aspectRatio = "{$width}/{$height}";

        return new ImageVariantDTO(
            assetId: (string) Str::uuid(),
            altText: pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            originalWidth: $width,
            originalHeight: $height,
            aspectRatio: $aspectRatio,
            blurHash: null,
            tinyBase64Placeholder: $blurPlaceholder,
            avifUrls: $avifUrls,
            webpUrls: $webpUrls,
            originalUrls: $originalUrls
        );
    }

    public function generateBlurPlaceholder(string $absolutePath): string
    {
        try {
            if (file_exists($absolutePath)) {
                $image = Image::read($absolutePath);
                $image->scale(width: 16);
                $encoded = (string) $image->toWebp(quality: 20);
                return 'data:image/webp;base64,' . base64_encode($encoded);
            }
        } catch (\Throwable $e) {
            Log::debug("Placeholder generation skipped: {$e->getMessage()}");
        }

        // 1x1 transparent SVG fallback
        return 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxNiAxNiI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2YwZWVlYSIvPjwvc3ZnPg==';
    }
}
