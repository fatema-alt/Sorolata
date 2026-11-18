<?php

declare(strict_types=1);

namespace App\Services\Story;

use App\Models\Product;
use App\Models\Story;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;

class StoryParserService
{
    /**
     * Regex pattern to identify shoppable product shortcodes.
     * Examples: [product:the-imperial-sonargaon-muslin-scarf] or [product:1]
     */
    protected const PATTERN = '/\[product:([a-zA-Z0-9\-_]+)\]/i';

    /**
     * Parses the story text and replaces product shortcodes with interactive shoppable cards.
     */
    public function parse(string $content, ?Story $story = null): string
    {
        if (empty($content) || ! str_contains($content, '[product:')) {
            return $this->formatParagraphs($content);
        }

        preg_match_all(self::PATTERN, $content, $matches);
        $identifiers = array_unique($matches[1] ?? []);

        if (empty($identifiers)) {
            return $this->formatParagraphs($content);
        }

        // Fetch products by slug or ID with relationships
        $products = Product::with(['artisan', 'district', 'media'])
            ->where(function ($query) use ($identifiers) {
                $query->whereIn('slug', $identifiers)
                    ->orWhereIn('id', array_filter($identifiers, 'is_numeric'));
            })
            ->get();

        $productMap = [];
        foreach ($products as $p) {
            $productMap[$p->slug] = $p;
            $productMap[(string) $p->id] = $p;
        }

        // Replace each shortcode with rendered card
        $parsed = preg_replace_callback(self::PATTERN, function ($match) use ($productMap) {
            $key = $match[1];
            if (isset($productMap[$key])) {
                return View::make('components.shoppable-product-card', [
                    'product' => $productMap[$key],
                ])->render();
            }

            return ''; // Remove broken shortcode quietly
        }, $content);

        return $this->formatParagraphs((string) $parsed);
    }

    /**
     * Extract all products referenced in shortcodes within the text.
     *
     * @return Collection<int, Product>
     */
    public function extractProducts(string $content): Collection
    {
        if (empty($content) || ! str_contains($content, '[product:')) {
            return collect();
        }

        preg_match_all(self::PATTERN, $content, $matches);
        $identifiers = array_unique($matches[1] ?? []);

        if (empty($identifiers)) {
            return collect();
        }

        return Product::with(['artisan', 'district', 'media'])
            ->where(function ($query) use ($identifiers) {
                $query->whereIn('slug', $identifiers)
                    ->orWhereIn('id', array_filter($identifiers, 'is_numeric'));
            })
            ->get();
    }

    /**
     * Ensures clean paragraph line breaks if the input is plain text with double newlines.
     */
    protected function formatParagraphs(string $content): string
    {
        if (str_contains($content, '<p>') || str_contains($content, '<div')) {
            return $content;
        }

        $paragraphs = array_filter(array_map('trim', explode("\n\n", $content)));
        if (empty($paragraphs)) {
            return '<p>' . nl2br($content) . '</p>';
        }

        return implode("\n\n", array_map(function ($p) {
            // Don't wrap if it's already an HTML element (like our shoppable card widget)
            if (str_starts_with($p, '<div') || str_starts_with($p, '<section')) {
                return $p;
            }
            return '<p class="mb-6 leading-relaxed">' . nl2br($p) . '</p>';
        }, $paragraphs));
    }
}
