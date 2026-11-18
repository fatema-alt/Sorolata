<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Http\Resources\Api\V1\CollectionResource;
use App\Http\Resources\Api\V1\ProductListResource;
use App\Models\Category;
use App\Models\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaxonomyApiController extends BaseApiController
{
    /**
     * List all active categories
     */
    public function categories(): JsonResponse
    {
        $categories = Category::withCount('products')
            ->orderBy('sort_order')
            ->get();

        return $this->respondWithSuccess(
            CategoryResource::collection($categories),
            'Categories retrieved successfully.'
        );
    }

    /**
     * Category details with paginated products
     */
    public function category(Request $request, string $slug): JsonResponse
    {
        $category = Category::where('slug', $slug)
            ->withCount('products')
            ->firstOrFail();

        $limit = min(50, max(1, (int) $request->input('limit', 16)));
        $products = $category->products()
            ->where('status', 'published')
            ->with(['category', 'district', 'artisan', 'media'])
            ->latest()
            ->paginate($limit);

        return $this->respondWithSuccess([
            'category' => new CategoryResource($category),
            'products' => ProductListResource::collection($products),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
            ],
        ], 'Category details retrieved successfully.');
    }

    /**
     * List all curated collections
     */
    public function collections(): JsonResponse
    {
        $collections = Collection::withCount('products')
            ->where('is_featured', true)
            ->latest()
            ->get();

        return $this->respondWithSuccess(
            CollectionResource::collection($collections),
            'Collections retrieved successfully.'
        );
    }

    /**
     * Collection details with products
     */
    public function collection(Request $request, string $slug): JsonResponse
    {
        $collection = Collection::where('slug', $slug)
            ->withCount('products')
            ->firstOrFail();

        $limit = min(50, max(1, (int) $request->input('limit', 16)));
        $products = $collection->products()
            ->where('status', 'published')
            ->with(['category', 'district', 'artisan', 'media'])
            ->paginate($limit);

        return $this->respondWithSuccess([
            'collection' => new CollectionResource($collection),
            'products' => ProductListResource::collection($products),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
            ],
        ], 'Collection details retrieved successfully.');
    }
}
