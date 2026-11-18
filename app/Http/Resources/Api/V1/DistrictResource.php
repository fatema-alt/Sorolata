<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin District
 */
class DistrictResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'slug' => $this->slug,
            'name_en' => $this->name_en,
            'name_bn' => $this->name_bn,
            'division' => $this->division->value ?? 'dhaka',
            'division_name' => $this->divisionRel?->name,
            'gi_specialty' => $this->gi_specialty,
            'historical_narrative' => $this->historical_narrative,
            'river_basin' => $this->river_basin,
            'latitude' => $this->latitude ? (float) $this->latitude : null,
            'longitude' => $this->longitude ? (float) $this->longitude : null,
            'artisans_count' => $this->whenCounted('artisans'),
            'artisans' => ArtisanResource::collection($this->whenLoaded('artisans')),
            'products' => ProductListResource::collection($this->whenLoaded('products')),
        ];
    }
}
