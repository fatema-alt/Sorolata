<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Cart
 */
class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'currency' => $this->currency ?? 'BDT',
            'items_count' => $this->items->sum('quantity'),
            'total_amount' => (float) $this->total_amount,
            'coupon_code' => $this->coupon_code,
            'items' => CartItemResource::collection($this->items),
        ];
    }
}
