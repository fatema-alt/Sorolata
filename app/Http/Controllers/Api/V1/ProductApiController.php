<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\V1\ProductDetailResource;
use App\Http\Resources\Api\V1\ProductListResource;
use App\Http\Resources\Api\V1\ReviewResource;
use App\Models\Product;
use App\Models\Review;
use App\Services\Api\ProductCatalogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductApiController extends BaseApiController
{
    public function __construct(
        protected ProductCatalogService $catalogService
    ) {}

    /**
     * Filtered & paginated product catalog
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = min(50, max(1, (int) $request->input('limit', 16)));
        $products = $this->catalogService->getFilteredProducts($request->all(), $perPage);

        return $this->respondWithPaginatedResource(
            ProductListResource::collection($products),
            'Products retrieved successfully.'
        );
    }

    /**
     * Single product details with variants, content, provenance, and specifications
     */
    public function show(string $uuidOrSlug): JsonResponse
    {
        $product = Product::with([
            'category',
            'district.divisionRel',
            'artisan.workshop',
            'variants',
            'media',
            'content',
            'origin.craftType',
            'materials',
            'specifications',
            'faqs',
            'seo',
        ])
        ->where('uuid', $uuidOrSlug)
        ->orWhere('slug', $uuidOrSlug)
        ->orWhere('id', is_numeric($uuidOrSlug) ? (int) $uuidOrSlug : 0)
        ->firstOrFail();

        return $this->respondWithSuccess(
            new ProductDetailResource($product),
            'Product details retrieved successfully.'
        );
    }

    /**
     * Get related products based on category, district, or relationships
     */
    public function related(int|string $id): JsonResponse
    {
        $product = Product::where('id', is_numeric($id) ? (int) $id : 0)
            ->orWhere('slug', (string) $id)
            ->orWhere('uuid', (string) $id)
            ->firstOrFail();

        $related = Product::where('status', 'published')
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product): void {
                $q->where('category_id', $product->category_id)
                  ->orWhere('district_id', $product->district_id);
            })
            ->with(['category', 'district', 'artisan', 'media'])
            ->limit(6)
            ->get();

        return $this->respondWithSuccess(
            ProductListResource::collection($related),
            'Related products retrieved successfully.'
        );
    }

    /**
     * Get paginated verified reviews for product
     */
    public function reviews(int|string $id): JsonResponse
    {
        $product = Product::where('id', is_numeric($id) ? (int) $id : 0)
            ->orWhere('slug', (string) $id)
            ->orWhere('uuid', (string) $id)
            ->firstOrFail();

        $reviews = Review::where('product_id', $product->id)
            ->where('is_published', true)
            ->with('user')
            ->latest()
            ->paginate(10);

        return $this->respondWithPaginatedResource(
            ReviewResource::collection($reviews),
            'Reviews retrieved successfully.'
        );
    }

    /**
     * Submit collector review
     */
    public function submitReview(Request $request, int|string $id): JsonResponse
    {
        $product = Product::where('id', is_numeric($id) ? (int) $id : 0)
            ->orWhere('slug', (string) $id)
            ->orWhere('uuid', (string) $id)
            ->firstOrFail();

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['required', 'string', 'max:150'],
            'comment' => ['required', 'string', 'max:2000'],
        ]);

        $review = Review::create([
            'product_id' => $product->id,
            'user_id' => $request->user()->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'comment' => $validated['comment'],
            'is_verified_purchase' => true,
            'is_published' => true, // auto-publish verified collector review
        ]);

        return $this->respondWithSuccess(
            new ReviewResource($review->load('user')),
            'Review submitted successfully.',
            201
        );
    }
}
