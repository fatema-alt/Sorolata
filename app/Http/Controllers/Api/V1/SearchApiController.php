<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\V1\ProductListResource;
use App\Models\Artisan;
use App\Models\District;
use App\Models\Product;
use App\Models\SearchLog;
use App\Models\Story;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchApiController extends BaseApiController
{
    /**
     * Unified multi-entity search
     */
    public function search(Request $request): JsonResponse
    {
        $query = trim((string) $request->input('q', ''));
        if (strlen($query) < 2) {
            return $this->respondWithSuccess([
                'products' => [],
                'districts' => [],
                'artisans' => [],
                'stories' => [],
            ], 'Query must be at least 2 characters.');
        }

        $term = '%' . $query . '%';

        // 1. Products
        $products = Product::where('status', 'published')
            ->where(function ($q) use ($term): void {
                $q->where('title', 'like', $term)
                  ->orWhere('subtitle', 'like', $term)
                  ->orWhere('sku', 'like', $term);
            })
            ->with(['category', 'district', 'artisan', 'media'])
            ->limit(10)
            ->get();

        // 2. Districts
        $districts = District::where('name_en', 'like', $term)
            ->orWhere('name_bn', 'like', $term)
            ->orWhere('gi_specialty', 'like', $term)
            ->limit(4)
            ->get(['id', 'uuid', 'name_en', 'name_bn', 'gi_specialty', 'river_basin']);

        // 3. Artisans
        $artisans = Artisan::where('full_name', 'like', $term)
            ->orWhere('atelier_name', 'like', $term)
            ->limit(4)
            ->get(['id', 'uuid', 'full_name', 'atelier_name', 'portrait_url', 'is_verified']);

        // 4. Gazette Stories
        $stories = Story::where('is_published', true)
            ->where(function ($q) use ($term): void {
                $q->where('title', 'like', $term)
                  ->orWhere('excerpt', 'like', $term);
            })
            ->limit(3)
            ->get(['id', 'uuid', 'slug', 'title', 'category', 'reading_time']);

        // Log search query for analytics
        SearchLog::create([
            'query' => $query,
            'results_count' => $products->count() + $districts->count() + $artisans->count(),
            'user_id' => $request->user('sanctum')?->id,
            'ip_hash' => hash('sha256', (string) $request->ip()),
            'session_id' => $request->header('X-Session-ID'),
            'created_at' => now(),
        ]);

        return $this->respondWithSuccess([
            'query' => $query,
            'products' => ProductListResource::collection($products),
            'districts' => $districts,
            'artisans' => $artisans,
            'stories' => $stories,
        ], 'Search results retrieved successfully.');
    }

    /**
     * Autocomplete suggestions & trending keywords
     */
    public function suggestions(Request $request): JsonResponse
    {
        $query = trim((string) $request->input('q', ''));

        $suggestions = [];
        if (!empty($query)) {
            $suggestions = Product::where('status', 'published')
                ->where('title', 'like', '%' . $query . '%')
                ->limit(5)
                ->pluck('title')
                ->toArray();
        }

        $trending = [
            'Jamdani Saree',
            'Tangail Handloom',
            'Rajshahi Silk',
            'Dhamrai Brass',
            'Nakshi Kantha',
            'Terracotta Planter',
        ];

        return $this->respondWithSuccess([
            'suggestions' => $suggestions,
            'trending' => $trending,
        ], 'Suggestions retrieved successfully.');
    }
}
