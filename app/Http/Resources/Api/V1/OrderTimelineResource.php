<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\OrderTimeline;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin OrderTimeline
 */
class OrderTimelineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->status,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
