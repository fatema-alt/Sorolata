<?php

declare(strict_types=1);

namespace App\Services\Builder;

use App\Models\Artisan;
use App\Models\District;
use App\Models\Product;

class DynamicDataParserService
{
    /**
     * Replaces dynamic tags (e.g. {{ product.title }}) with real model values.
     */
    public function parse(string $content, ?Product $product = null, ?Artisan $artisan = null, ?District $district = null): string
    {
        if (empty($content) || ! str_contains($content, '{{')) {
            return $content;
        }

        $artisan = $artisan ?? $product?->artisan;
        $district = $district ?? $product?->district ?? $artisan?->district;

        $replacements = [
            '{{ product.title }}' => $product?->title ?? 'Handcrafted Bengali Heirloom',
            '{{ product.price }}' => $product ? $product->formatted_price : '৳ 18,500',
            '{{ product.compare_price }}' => $product?->formatted_compare_price ?? '',
            '{{ product.sku }}' => $product?->sku ?? 'SRL-GEN-001',
            '{{ product.subtitle }}' => $product?->subtitle ?? '',
            
            '{{ artisan.name }}' => $artisan?->full_name ?? 'Master Craftsman Guild',
            '{{ artisan.atelier }}' => $artisan?->atelier_name ?? 'Bengal Heritage Loom',
            '{{ artisan.craft }}' => $artisan?->craft_type?->title() ?? 'Heritage Handloom Weaving',
            '{{ artisan.quote }}' => $artisan?->quote ?? 'Preserving centuries of generational dedication.',
            '{{ artisan.experience }}' => (string) ($artisan?->years_of_mastery ?? 35),

            '{{ district.name }}' => $district?->name_en ?? 'Bengal Heritage Basin',
            '{{ district.name_bn }}' => $district?->name_bn ?? 'বাংলাদেশ',
            '{{ district.gi_specialty }}' => $district?->gi_specialty ?? 'GI Certified Heritage Craft',
            '{{ district.river_basin }}' => $district?->river_basin ?? 'Shitalakshya River Basin',
        ];

        return strtr($content, $replacements);
    }

    /**
     * Parses an entire settings array recursively.
     *
     * @param array<string, mixed> $settings
     * @return array<string, mixed>
     */
    public function parseSettings(array $settings, ?Product $product = null, ?Artisan $artisan = null, ?District $district = null): array
    {
        $parsed = [];

        foreach ($settings as $key => $value) {
            if (is_string($value)) {
                $parsed[$key] = $this->parse($value, $product, $artisan, $district);
            } elseif (is_array($value)) {
                $parsed[$key] = $this->parseSettings($value, $product, $artisan, $district);
            } else {
                $parsed[$key] = $value;
            }
        }

        return $parsed;
    }

    /**
     * Converts common video URLs (YouTube, Vimeo, direct MP4) into embeddable URLs or types.
     *
     * @return array{type: string, embed_url: string|null}
     */
    public function parseVideoUrl(?string $url): array
    {
        if (empty($url)) {
            return ['type' => 'none', 'embed_url' => null];
        }

        $url = trim($url);

        // YouTube
        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
            return [
                'type' => 'youtube',
                'embed_url' => "https://www.youtube-nocookie.com/embed/{$matches[1]}?autoplay=0&rel=0",
            ];
        }

        // Vimeo
        if (preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)/', $url, $matches)) {
            $vimeoId = end($matches);
            return [
                'type' => 'vimeo',
                'embed_url' => "https://player.vimeo.com/video/{$vimeoId}",
            ];
        }

        // Direct video file
        if (preg_match('/\.(mp4|webm|ogg)$/i', $url)) {
            return [
                'type' => 'mp4',
                'embed_url' => $url,
            ];
        }

        return [
            'type' => 'embed',
            'embed_url' => $url,
        ];
    }
}
