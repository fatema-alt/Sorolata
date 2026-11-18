<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasBilingualAttributes;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use HasFactory, HasUuid, SoftDeletes, HasBilingualAttributes;

    protected $fillable = [
        'uuid',
        'slug',
        'title',
        'title_bn',
        'name_bn',
        'type',
        'rules',
        'description',
        'description_bn',
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

    public function getNameAttribute(): ?string
    {
        return $this->getLocalized('title');
    }

    public function getLocalized(string $attribute, ?string $locale = null): ?string
    {
        if ($attribute === 'name') {
            $attribute = 'title';
        }

        $locale = $locale ?? app()->getLocale();

        if ($locale === 'bn') {
            $bnValue = $this->getAttribute("{$attribute}_bn");
            if (!empty($bnValue)) {
                return (string) $bnValue;
            }
            if ($attribute === 'title' && !empty($this->name_bn)) {
                return (string) $this->name_bn;
            }
        }

        $val = $this->getAttribute($attribute);
        return $val !== null ? (string) $val : null;
    }

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
