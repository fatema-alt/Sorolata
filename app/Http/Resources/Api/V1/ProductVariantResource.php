<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductVariant
 */
class ProductVariantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'title' => $this->title,
            'price' => (float) $this->price,
            'compare_at_price' => $this->compare_at_price ? (float) $this->compare_at_price : null,
            'stock_quantity' => (int) $this->stock_quantity,
            'is_in_stock' => $this->stock_quantity > 0,
            'weight' => $this->weight ? (float) $this->weight : null,
            'attributes' => $this->attributes ?? [],
            'image_url' => $this->image_url,
            'is_active' => (bool) $this->is_active,
        ];
    }
}
