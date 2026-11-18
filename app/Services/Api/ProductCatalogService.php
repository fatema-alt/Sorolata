<?php

declare(strict_types=1);

namespace App\Services\Api;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ProductCatalogService
{
    /**
     * Filter and paginate products with eager loaded relationships
     */
    public function getFilteredProducts(array $filters = [], int $perPage = 16): LengthAwarePaginator
    {
        $query = Product::query()
            ->with(['category', 'district', 'artisan', 'media'])
            ->where('status', 'published');

        // Search query
        if (!empty($filters['search'])) {
            $term = '%' . trim((string) $filters['search']) . '%';
            $query->where(function (Builder $q) use ($term): void {
                $q->where('title', 'like', $term)
                  ->orWhere('subtitle', 'like', $term)
                  ->orWhere('sku', 'like', $term)
                  ->orWhereHas('district', fn(Builder $dq) => $dq->where('name_en', 'like', $term))
                  ->orWhereHas('artisan', fn(Builder $aq) => $aq->where('full_name', 'like', $term));
            });
        }

        // Category filter
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        } elseif (!empty($filters['category_slug'])) {
            $query->whereHas('category', fn(Builder $q) => $q->where('slug', $filters['category_slug']));
        }

        // Collection filter
        if (!empty($filters['collection_id'])) {
            $query->whereHas('collections', fn(Builder $q) => $q->where('collections.id', $filters['collection_id']));
        } elseif (!empty($filters['collection_slug'])) {
            $query->whereHas('collections', fn(Builder $q) => $q->where('collections.slug', $filters['collection_slug']));
        }

        // District filter
        if (!empty($filters['district_id'])) {
            $query->where('district_id', $filters['district_id']);
        } elseif (!empty($filters['district_name'])) {
            $query->whereHas('district', fn(Builder $q) => $q->where('name_en', $filters['district_name']));
        }

        // Artisan filter
        if (!empty($filters['artisan_id'])) {
            $query->where('artisan_id', $filters['artisan_id']);
        }

        // Material filter
        if (!empty($filters['material_id'])) {
            $query->whereHas('materials', fn(Builder $q) => $q->where('materials.id', $filters['material_id']));
        }

        // Price range
        if (isset($filters['min_price']) && is_numeric($filters['min_price'])) {
            $query->where('price', '>=', (float) $filters['min_price']);
        }
        if (isset($filters['max_price']) && is_numeric($filters['max_price'])) {
            $query->where('price', '<=', (float) $filters['max_price']);
        }

        // In stock only
        if (!empty($filters['in_stock'])) {
            $query->where(function (Builder $q): void {
                $q->where('is_unlimited_stock', true)
                  ->orWhere('stock_quantity', '>', 0);
            });
        }

        // Sorting
        $sort = $filters['sort'] ?? 'newest';
        match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'popular' => $query->orderByDesc('is_featured')->orderByDesc('id'),
            'title_asc' => $query->orderBy('title', 'asc'),
            default => $query->orderByDesc('id'),
        };

        return $query->paginate($perPage);
    }
}
