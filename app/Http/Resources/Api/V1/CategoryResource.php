<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Category
 */
class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'hero_image' => $this->hero_image,
            'icon_url' => $this->icon_url,
            'sort_order' => (int) $this->sort_order,
            'products_count' => $this->whenCounted('products'),
            'products' => ProductListResource::collection($this->whenLoaded('products')),
        ];
    }
}
