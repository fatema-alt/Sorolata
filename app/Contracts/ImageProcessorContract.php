<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\ImageVariantDTO;
use Illuminate\Http\UploadedFile;

interface ImageProcessorContract
{
    /**
     * Process an uploaded master image file into responsive variants.
     */
    public function processUpload(UploadedFile $file, string $disk = 'public', string $directory = 'media'): ImageVariantDTO;

    /**
     * Generates a tiny base64 placeholder for near-zero CLS.
     */
    public function generateBlurPlaceholder(string $absolutePath): string;
}
