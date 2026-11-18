<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Page
 */
class PageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'slug' => $this->slug,
            'title' => $this->title,
            'page_type' => $this->page_type,
            'layout_json' => $this->layout_json ?? [],
            'seo' => $this->seo ? [
                'meta_title' => $this->seo->meta_title,
                'meta_description' => $this->seo->meta_description,
                'canonical_url' => $this->seo->canonical_url,
            ] : null,
            'published_at' => $this->published_at?->toIso8601String(),
        ];
    }
}
