<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Order
 */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'order_number' => $this->order_number,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'payment_status' => $this->payment_status,
            'payment_gateway' => $this->payment_gateway,
            'currency' => $this->currency,
            'subtotal_amount' => (float) $this->subtotal_amount,
            'discount_amount' => (float) $this->discount_amount,
            'shipping_fee' => (float) $this->shipping_fee,
            'total_amount' => (float) $this->total_amount,
            'customer' => [
                'name' => $this->customer_name,
                'email' => $this->customer_email,
                'phone' => $this->customer_phone,
            ],
            'shipping_address' => $this->shipping_address ?? [],
            'billing_address' => $this->billing_address ?? [],
            'tracking' => [
                'courier_name' => $this->courier_name,
                'tracking_number' => $this->tracking_number,
                'tracking_url' => $this->tracking_url,
                'dispatched_at' => $this->dispatched_at?->toIso8601String(),
                'delivered_at' => $this->delivered_at?->toIso8601String(),
            ],
            'items' => OrderItemResource::collection($this->items),
            'timeline' => OrderTimelineResource::collection($this->timeline),
            'timelines' => OrderTimelineResource::collection($this->timeline),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
