<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasBilingualAttributes;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Story extends Model
{
    use HasFactory, HasUuid, SoftDeletes, HasBilingualAttributes;

    protected $fillable = [
        'uuid',
        'slug',
        'title',
        'title_bn',
        'excerpt',
        'excerpt_bn',
        'content',
        'content_bn',
        'category',
        'category_bn',
        'author_name',
        'author_name_bn',
        'hero_image',
        'reading_time',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'reading_time' => 'integer',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function tags(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function seo(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }

    public function mediaAttachments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(MediaAttachment::class, 'mediable')->orderBy('sort_order');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'story_product')
            ->withPivot(['sort_order', 'context_note_en', 'context_note_bn'])
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }
}
