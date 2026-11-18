<?php

declare(strict_types=1);

namespace App\Services\Builder;

use App\Models\Artisan;
use App\Models\District;
use App\Models\Product;

class DynamicDataParserService
{
    /**
     * Resolve bilingual {en, bn} array or JSON string with English fallback.
     */
    public function resolveBilingualValue(mixed $value, ?string $locale = null): mixed
    {
        $locale = $locale ?? app()->getLocale();

        if (is_array($value) && (isset($value['en']) || isset($value['bn']))) {
            if ($locale === 'bn' && !empty($value['bn'])) {
                return $value['bn'];
            }
            return $value['en'] ?? ($value['bn'] ?? '');
        }

        if (is_string($value) && (str_starts_with(trim($value), '{') && (str_contains($value, '"en"') || str_contains($value, '"bn"')))) {
            $decoded = json_decode($value, true);
            if (is_array($decoded) && (isset($decoded['en']) || isset($decoded['bn']))) {
                if ($locale === 'bn' && !empty($decoded['bn'])) {
                    return $decoded['bn'];
                }
                return $decoded['en'] ?? ($decoded['bn'] ?? '');
            }
        }

        return $value;
    }

    /**
     * Replaces dynamic tags (e.g. {{ product.title }}) with real model values.
     */
    public function parse(mixed $content, ?Product $product = null, ?Artisan $artisan = null, ?District $district = null): string
    {
        $content = $this->resolveBilingualValue($content);
        if (!is_string($content) || empty($content)) {
            return is_scalar($content) ? (string) $content : '';
        }

        if (!str_contains($content, '{{')) {
            return $content;
        }

        $artisan = $artisan ?? $product?->artisan;
        $district = $district ?? $product?->district ?? $artisan?->district;

        $replacements = [
            '{{ product.title }}' => $product ? ($product->getLocalized('title') ?? $product->title) : __('Handcrafted Bengali Heirloom'),
            '{{ product.price }}' => $product ? $product->formatted_price : '৳ 18,500',
            '{{ product.compare_price }}' => $product?->formatted_compare_price ?? '',
            '{{ product.sku }}' => $product?->sku ?? 'SRL-GEN-001',
            '{{ product.subtitle }}' => $product ? ($product->getLocalized('subtitle') ?? $product->subtitle) : '',
            
            '{{ artisan.name }}' => $artisan ? ($artisan->getLocalized('full_name') ?? $artisan->full_name) : __('Master Craftsman Guild'),
            '{{ artisan.atelier }}' => $artisan ? ($artisan->getLocalized('atelier_name') ?? $artisan->atelier_name) : __('Bengal Heritage Loom'),
            '{{ artisan.craft }}' => $artisan?->craft_type?->title() ?? __('Heritage Handloom Weaving'),
            '{{ artisan.quote }}' => $artisan ? ($artisan->getLocalized('quote') ?? $artisan->quote) : __('Preserving centuries of generational dedication.'),
            '{{ artisan.experience }}' => (string) ($artisan?->years_of_mastery ?? 35),

            '{{ district.name }}' => $district ? ($district->getLocalized('name') ?? $district->name_en) : __('Bengal Heritage Basin'),
            '{{ district.name_bn }}' => $district?->name_bn ?? 'বাংলাদেশ',
            '{{ district.gi_specialty }}' => $district ? ($district->getLocalized('gi_specialty') ?? $district->gi_specialty) : __('GI Certified Heritage Craft'),
            '{{ district.river_basin }}' => $district ? ($district->getLocalized('river_basin') ?? $district->river_basin) : __('Shitalakshya River Basin'),
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
            $value = $this->resolveBilingualValue($value);

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
