<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Story
 */
class StoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'slug' => $this->slug,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'category' => $this->category,
            'author_name' => $this->author_name,
            'hero_image' => $this->hero_image,
            'reading_time' => (int) $this->reading_time,
            'published_at' => $this->published_at?->toIso8601String(),
        ];
    }
}
