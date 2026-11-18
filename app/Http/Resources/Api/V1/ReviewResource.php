<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Review
 */
class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rating' => (int) $this->rating,
            'title' => $this->title,
            'comment' => $this->comment,
            'customer_name' => $this->user?->name ?? 'Collector',
            'is_verified_purchase' => (bool) $this->is_verified_purchase,
            'official_curator_reply' => $this->curator_reply,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
