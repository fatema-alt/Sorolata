<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Division
 */
class DivisionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'bn_name' => $this->bn_name,
            'slug' => $this->slug,
            'coordinates' => $this->coordinates ?? [],
            'districts_count' => $this->districts()->count(),
            'districts' => DistrictResource::collection($this->whenLoaded('districts')),
        ];
    }
}
