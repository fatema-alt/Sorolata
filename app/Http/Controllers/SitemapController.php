<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\Category;
use App\Models\Collection;
use App\Models\District;
use App\Models\Page;
use App\Models\Product;
use App\Models\Story;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [];

        // Static Key Commercial & Institutional Pages
        $staticPages = [
            ['url' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily', 'lastmod' => now()->toAtomString()],
            ['url' => route('shop.index'), 'priority' => '0.9', 'changefreq' => 'daily', 'lastmod' => now()->toAtomString()],
            ['url' => route('districts.index'), 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => now()->toAtomString()],
            ['url' => route('artisans.index'), 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => now()->toAtomString()],
            ['url' => route('stories.index'), 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => now()->toAtomString()],
            ['url' => route('pages.about'), 'priority' => '0.7', 'changefreq' => 'monthly', 'lastmod' => now()->toAtomString()],
            ['url' => route('pages.contact'), 'priority' => '0.6', 'changefreq' => 'monthly', 'lastmod' => now()->toAtomString()],
        ];

        foreach ($staticPages as $page) {
            $urls[] = $page;
        }

        // Published Products
        Product::where('status', 'published')
            ->select('slug', 'updated_at')
            ->chunk(100, function ($products) use (&$urls): void {
                foreach ($products as $product) {
                    if (!empty($product->slug)) {
                        $urls[] = [
                            'url' => route('products.show', $product->slug),
                            'priority' => '0.9',
                            'changefreq' => 'daily',
                            'lastmod' => $product->updated_at?->toAtomString() ?? now()->toAtomString(),
                        ];
                    }
                }
            });

        // Categories
        Category::select('slug', 'updated_at')->get()->each(function ($category) use (&$urls): void {
            if (!empty($category->slug)) {
                $urls[] = [
                    'url' => route('category.show', $category->slug),
                    'priority' => '0.8',
                    'changefreq' => 'weekly',
                    'lastmod' => $category->updated_at?->toAtomString() ?? now()->toAtomString(),
                ];
            }
        });

        // Curated Collections
        Collection::select('slug', 'updated_at')->get()->each(function ($collection) use (&$urls): void {
            if (!empty($collection->slug)) {
                $urls[] = [
                    'url' => route('collection.show', $collection->slug),
                    'priority' => '0.8',
                    'changefreq' => 'weekly',
                    'lastmod' => $collection->updated_at?->toAtomString() ?? now()->toAtomString(),
                ];
            }
        });

        // Heritage Districts
        District::select('slug', 'updated_at')->get()->each(function ($district) use (&$urls): void {
            if (!empty($district->slug)) {
                $urls[] = [
                    'url' => route('district.show', $district->slug),
                    'priority' => '0.7',
                    'changefreq' => 'monthly',
                    'lastmod' => $district->updated_at?->toAtomString() ?? now()->toAtomString(),
                ];
            }
        });

        // Master Artisans
        Artisan::where('is_verified', true)
            ->select('slug', 'updated_at')
            ->get()
            ->each(function ($artisan) use (&$urls): void {
                if (!empty($artisan->slug)) {
                    $urls[] = [
                        'url' => route('artisan.show', $artisan->slug),
                        'priority' => '0.7',
                        'changefreq' => 'monthly',
                        'lastmod' => $artisan->updated_at?->toAtomString() ?? now()->toAtomString(),
                    ];
                }
            });

        // Editorial Gazette Stories
        Story::where('is_published', true)
            ->select('slug', 'published_at', 'updated_at')
            ->get()
            ->each(function ($story) use (&$urls): void {
                if (!empty($story->slug)) {
                    $urls[] = [
                        'url' => route('stories.show', $story->slug),
                        'priority' => '0.7',
                        'changefreq' => 'weekly',
                        'lastmod' => ($story->published_at ?? $story->updated_at)?->toAtomString() ?? now()->toAtomString(),
                    ];
                }
            });

        // Visual Builder & Static CMS Pages
        Page::where('status', 'published')
            ->select('slug', 'updated_at')
            ->get()
            ->each(function ($page) use (&$urls): void {
                if (!empty($page->slug) && !in_array($page->slug, ['home', 'about', 'contact'], true)) {
                    $urls[] = [
                        'url' => route('builder.page.show', $page->slug),
                        'priority' => '0.6',
                        'changefreq' => 'monthly',
                        'lastmod' => $page->updated_at?->toAtomString() ?? now()->toAtomString(),
                    ];
                }
            });

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $item) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($item['url'], ENT_XML1, 'UTF-8') . '</loc>';
            $xml .= '<lastmod>' . htmlspecialchars($item['lastmod'], ENT_XML1, 'UTF-8') . '</lastmod>';
            $xml .= '<changefreq>' . htmlspecialchars($item['changefreq'], ENT_XML1, 'UTF-8') . '</changefreq>';
            $xml .= '<priority>' . htmlspecialchars($item['priority'], ENT_XML1, 'UTF-8') . '</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
