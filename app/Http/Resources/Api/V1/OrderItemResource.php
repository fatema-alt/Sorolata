<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin OrderItem
 */
class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_title' => $this->product_title,
            'product_sku' => $this->product_sku,
            'variant_title' => $this->variant_title,
            'unit_price' => (float) $this->unit_price,
            'quantity' => (int) $this->quantity,
            'subtotal' => (float) $this->subtotal,
            'custom_options' => $this->custom_options ?? [],
            'artisan_name' => $this->artisan_name,
            'district_name' => $this->district_name,
        ];
    }
}
