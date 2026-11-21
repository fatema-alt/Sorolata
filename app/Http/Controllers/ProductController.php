<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\LayoutEngineContract;
use App\Contracts\SeoMetadataContract;
use App\Models\Artisan;
use App\Models\Category;
use App\Models\Collection;
use App\Models\District;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        protected LayoutEngineContract $layoutEngine,
        protected SeoMetadataContract $seoEngine
    ) {}

    public function index(): View
    {
        // 1. Fetch published builder sections if configured for Home page
        $homePage = Page::where('slug', 'home')->first();
        $builderSections = collect($homePage?->layout_json['sections'] ?? [])
            ->filter(fn ($s) => $s['is_active'] ?? true)
            ->keyBy('type');

        $featuredProducts = Product::with(['artisan', 'district', 'media'])
            ->where('status', 'published')
            ->orderBy('is_featured', 'desc')
            ->take(8)
            ->get();

        $bestSellers = Product::with(['artisan', 'district', 'media'])
            ->where('status', 'published')
            ->take(4)
            ->get();

        $trendingProducts = Product::with(['artisan', 'district', 'media'])
            ->where('status', 'published')
            ->orderBy('id', 'desc')
            ->take(4)
            ->get();

        $categories = Category::withCount('products')->get();

        $heritageDistricts = District::withCount('products')
            ->take(6)
            ->get();

        $masterArtisans = Artisan::with(['district'])
            ->where('is_verified', true)
            ->take(4)
            ->get();

        $collections = Collection::take(2)->get();

        $instaShots = [
            'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?q=80&w=600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?q=80&w=600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=600&auto=format&fit=crop',
        ];

        return view('storefront.index', [
            'products' => $featuredProducts,
            'bestSellers' => $bestSellers,
            'trendingProducts' => $trendingProducts,
            'categories' => $categories,
            'districts' => $heritageDistricts,
            'artisans' => $masterArtisans,
            'collections' => $collections,
            'builderSections' => $builderSections,
            'homePage' => $homePage,
            'instaShots' => $instaShots,
        ]);
    }

    public function show(string $slug): View|\Illuminate\Http\RedirectResponse
    {
        $product = Product::with(['artisan', 'district', 'media', 'category', 'stories', 'reviews' => fn($q) => $q->latest()])
            ->where('slug', $slug)
            ->orWhere('uuid', $slug)
            ->firstOrFail();

        if ($slug === $product->uuid && !empty($product->slug)) {
            return redirect()->route('products.show', $product->slug, 301);
        }

        $renderedBlocks = $this->layoutEngine->renderProductBlocks($product);
        $jsonLd = $this->seoEngine->generateProductJsonLd($product);
        $metaTags = $this->seoEngine->generateMetaTags($product);
        $relatedStories = $product->stories;
        $reviews = $product->reviews;
        $averageRating = $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : 5.0;

        return view('products.show', [
            'product' => $product,
            'renderedBlocks' => $renderedBlocks,
            'jsonLd' => $jsonLd,
            'metaTags' => $metaTags,
            'relatedStories' => $relatedStories,
            'reviews' => $reviews,
            'averageRating' => $averageRating,
        ]);
    }

    public function submitReview(Request $request, string $slug): \Illuminate\Http\RedirectResponse
    {
        $product = Product::where('slug', $slug)->orWhere('uuid', $slug)->firstOrFail();

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'author_name' => 'required|string|max:100',
            'author_location' => 'nullable|string|max:100',
            'title' => 'nullable|string|max:150',
            'content' => 'required|string|max:2000',
        ]);

        $userId = auth()->id();
        $isVerified = false;

        if ($userId) {
            $isVerified = \App\Models\OrderItem::where('product_id', $product->id)
                ->whereHas('order', fn($q) => $q->where('user_id', $userId))
                ->exists();
        }

        \App\Models\Review::create([
            'product_id' => $product->id,
            'artisan_id' => $product->artisan_id,
            'user_id' => $userId,
            'author_name' => $validated['author_name'],
            'author_location' => $validated['author_location'] ?: 'ঢাকা, বাংলাদেশ',
            'rating' => (int) $validated['rating'],
            'title' => $validated['title'] ?? 'মাস্টারপিস কালেকশন',
            'content' => $validated['content'],
            'is_verified_purchase' => $isVerified,
            'likes_count' => 0,
        ]);

        return back()->with('review_success', __('Your review and rating have been added successfully. Thank you for your feedback!'));
    }
}
