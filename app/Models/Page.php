<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'uuid',
        'slug',
        'title',
        'page_type',
        'layout_json',
        'seo_meta',
        'status',
        'published_at',
        'version',
    ];

    protected $casts = [
        'layout_json' => 'array',
        'seo_meta' => 'array',
        'published_at' => 'datetime',
        'version' => 'integer',
    ];

    public function revisions(): HasMany
    {
        return $this->hasMany(PageRevision::class);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function seo(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }

    public function mediaAttachments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(MediaAttachment::class, 'mediable')->orderBy('sort_order');
    }
}
