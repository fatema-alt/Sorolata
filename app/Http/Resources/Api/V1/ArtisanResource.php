<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Artisan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Artisan
 */
class ArtisanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'slug' => $this->slug,
            'full_name' => $this->full_name,
            'atelier_name' => $this->atelier_name,
            'craft_type' => $this->craft_type->value ?? 'handloom',
            'years_of_mastery' => (int) $this->years_of_mastery,
            'biography' => $this->biography,
            'quote' => $this->quote,
            'portrait_url' => $this->portrait_url,
            'awards' => $this->awards ?? [],
            'is_verified' => (bool) $this->is_verified,
            'district' => $this->district ? [
                'id' => $this->district->id,
                'name_en' => $this->district->name_en,
                'name_bn' => $this->district->name_bn,
            ] : null,
            'workshop' => $this->workshop ? [
                'name' => $this->workshop->name,
                'location' => $this->workshop->location,
                'craft_specialty' => $this->workshop->craft_specialty,
                'founded_year' => $this->workshop->founded_year,
                'workers_count' => $this->workshop->workers_count,
            ] : null,
            'products_count' => $this->whenCounted('products'),
            'products' => ProductListResource::collection($this->whenLoaded('products')),
        ];
    }
}
