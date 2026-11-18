<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Product;

interface SeoMetadataContract
{
    /**
     * Generate Schema.org JSON-LD graph.
     *
     * @return array<string, mixed>
     */
    public function generateProductJsonLd(Product $product): array;

    /**
     * Generate OpenGraph and Twitter meta tags.
     *
     * @return array<string, string>
     */
    public function generateMetaTags(Product $product): array;
}
