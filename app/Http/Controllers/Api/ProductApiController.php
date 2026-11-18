<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Commerce\CommerceAnalyticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function __construct(
        protected CommerceAnalyticsService $analytics
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = Product::with(['artisan', 'district', 'category', 'media', 'variants'])
            ->where('status', 'published');

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->input('category')));
        }

        if ($request->filled('district')) {
            $query->whereHas('district', fn ($q) => $q->where('slug', $request->input('district')));
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('subtitle', 'like', "%{$term}%")
                  ->orWhere('sku', 'like', "%{$term}%");
            });
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'title_asc' => $query->orderBy('title', 'asc'),
            default => $query->latest('published_at'),
        };

        $products = $query->paginate((int) $request->input('per_page', 12));

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'total_pages' => $products->lastPage(),
                'total_items' => $products->total(),
                'per_page' => $products->perPage(),
            ],
        ]);
    }

    public function show(string $uuidOrSlug): JsonResponse
    {
        $product = Product::with([
            'artisan',
            'district',
            'category',
            'media',
            'variants' => fn ($q) => $q->where('is_active', true),
            'relationships.relatedProduct.media',
        ])
        ->where('uuid', $uuidOrSlug)
        ->orWhere('slug', $uuidOrSlug)
        ->firstOrFail();

        $this->analytics->recordProductView($product->id);

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }
}
