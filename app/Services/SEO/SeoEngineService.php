<?php

declare(strict_types=1);

namespace App\Services\SEO;

use App\Contracts\SeoMetadataContract;
use App\Models\Product;

class SeoEngineService implements SeoMetadataContract
{
    public function generateProductJsonLd(Product $product): array
    {
        $media = $product->media->first();
        $imageUrl = $media ? url($media->file_path) : url('/images/placeholder-heritage.jpg');

        $jsonLd = [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $product->title,
            'image' => [$imageUrl],
            'description' => $product->subtitle ?? "Authentic heritage craftsmanship: {$product->title}",
            'sku' => $product->sku ?: 'SRL-' . $product->id,
            'mpn' => $product->uuid,
            'brand' => [
                '@type' => 'Brand',
                'name' => 'Sorolata',
            ],
            'countryOfOrigin' => [
                '@type' => 'Country',
                'name' => 'Bangladesh',
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('products.show', $product->slug),
                'priceCurrency' => $product->currency?->value ?? 'BDT',
                'price' => number_format((float) ($product->price ?? 0), 2, '.', ''),
                'priceValidUntil' => now()->addYear()->format('Y-m-d'),
                'itemCondition' => 'https://schema.org/NewCondition',
                'availability' => ($product->stock_quantity > 0 && $product->is_in_stock)
                    ? 'https://schema.org/InStock'
                    : 'https://schema.org/OutOfStock',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => 'Sorolata Living Heritage',
                ],
            ],
        ];

        if ($product->reviews()->count() > 0) {
            $avg = round((float) $product->reviews()->avg('rating'), 1);
            $count = $product->reviews()->count();
            $jsonLd['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => (string) max(1, $avg),
                'reviewCount' => (string) max(1, $count),
            ];
        }

        if ($product->artisan) {
            $jsonLd['creator'] = [
                '@type' => 'Person',
                'name' => $product->artisan->full_name,
                'jobTitle' => $product->artisan->craft_type->title(),
                'knowsAbout' => $product->artisan->craft_type->title(),
            ];
            if ($product->artisan->portrait_url) {
                $jsonLd['creator']['image'] = url($product->artisan->portrait_url);
            }
        }

        if ($product->district) {
            $jsonLd['areaServed'] = [
                '@type' => 'AdministrativeArea',
                'name' => $product->district->name_en,
                'alternateName' => $product->district->name_bn,
            ];
        }

        return $jsonLd;
    }

    public function generateMetaTags(Product $product): array
    {
        $media = $product->media->first();
        $imageUrl = $media ? url($media->file_path) : url('/images/og-heritage.jpg');
        $canonicalUrl = route('products.show', $product->slug);

        return [
            'title' => "{$product->title} — Authentic Heritage Craft | Sorolata",
            'description' => $product->subtitle ?? "Handcrafted by master artisans in {$product->district?->name_en}, Bangladesh. Discover this heirloom piece.",
            'canonical' => $canonicalUrl,
            'og:title' => $product->title,
            'og:description' => $product->subtitle ?? 'Handcrafted heritage and luxury lifestyle piece from Bangladesh.',
            'og:image' => $imageUrl,
            'og:url' => $canonicalUrl,
            'og:type' => 'product',
            'twitter:card' => 'summary_large_image',
            'twitter:title' => $product->title,
            'twitter:description' => $product->subtitle ?? 'Handcrafted heritage and luxury lifestyle piece.',
            'twitter:image' => $imageUrl,
        ];
    }
}
