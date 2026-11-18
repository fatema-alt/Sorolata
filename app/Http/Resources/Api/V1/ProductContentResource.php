<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\ProductContent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductContent
 */
class ProductContentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'story_title' => $this->story_title,
            'story_narrative' => $this->story_narrative,
            'craft_technique' => $this->craft_technique,
            'heritage_significance' => $this->heritage_significance,
            'care_guide' => $this->care_guide,
            'video_embed_url' => $this->video_embed_url,
        ];
    }
}
