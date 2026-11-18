<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\ProductSpecification;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductSpecification
 */
class ProductSpecificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'group_name' => $this->group_name,
            'attribute_name' => $this->attribute_name,
            'attribute_value' => $this->attribute_value,
            'sort_order' => (int) $this->sort_order,
        ];
    }
}
