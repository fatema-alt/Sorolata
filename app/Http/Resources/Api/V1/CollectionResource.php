<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Collection
 */
class CollectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'cover_image' => $this->cover_image,
            'banner_url' => $this->banner_url,
            'is_featured' => (bool) $this->is_featured,
            'products_count' => $this->whenCounted('products'),
            'products' => ProductListResource::collection($this->whenLoaded('products')),
        ];
    }
}
