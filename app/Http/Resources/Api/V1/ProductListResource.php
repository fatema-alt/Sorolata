<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ProductListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'slug' => $this->slug,
            'title' => $this->title,
            'short_title' => $this->short_title ?? $this->title,
            'subtitle' => $this->subtitle,
            'sku' => $this->sku,
            'price' => (float) $this->price,
            'sale_price' => $this->sale_price ? (float) $this->sale_price : null,
            'effective_price' => (float) $this->effective_price,
            'formatted_price' => $this->formatted_price,
            'currency' => $this->currency->value ?? 'BDT',
            'is_in_stock' => (bool) $this->is_in_stock,
            'stock_quantity' => (int) $this->stock_quantity,
            'is_featured' => (bool) $this->is_featured,
            'hero_image' => $this->media->first()?->file_path ?? '/images/products/placeholder.webp',
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ] : null,
            'district' => $this->district ? [
                'id' => $this->district->id,
                'name_en' => $this->district->name_en,
                'name_bn' => $this->district->name_bn,
                'gi_specialty' => $this->district->gi_specialty,
            ] : null,
            'artisan' => $this->artisan ? [
                'id' => $this->artisan->id,
                'full_name' => $this->artisan->full_name,
                'atelier_name' => $this->artisan->atelier_name,
                'is_verified' => (bool) $this->artisan->is_verified,
            ] : null,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
