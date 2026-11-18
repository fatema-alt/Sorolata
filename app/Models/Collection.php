<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'uuid',
        'slug',
        'title',
        'type',
        'rules',
        'description',
        'cover_image',
        'banner_url',
        'icon_url',
        'seo_meta',
        'layout_json',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'rules' => 'array',
        'seo_meta' => 'array',
        'layout_json' => 'array',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'collection_product')->withPivot('sort_order')->orderByPivot('sort_order');
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
