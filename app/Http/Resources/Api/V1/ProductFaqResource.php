<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\ProductFaq;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductFaq
 */
class ProductFaqResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question' => $this->question,
            'answer' => $this->answer,
            'sort_order' => (int) $this->sort_order,
        ];
    }
}
