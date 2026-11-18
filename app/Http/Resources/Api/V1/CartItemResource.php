<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin CartItem
 */
class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_title' => $this->product?->title,
            'product_slug' => $this->product?->slug,
            'hero_image' => $this->product?->media->first()?->file_path ?? '/images/products/placeholder.webp',
            'variant_id' => $this->variant_id,
            'variant_title' => $this->variant?->title,
            'quantity' => (int) $this->quantity,
            'unit_price' => (float) $this->unit_price,
            'line_total' => (float) ($this->unit_price * $this->quantity),
            'custom_options' => $this->custom_options ?? [],
        ];
    }
}
