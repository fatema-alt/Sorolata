<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasBilingualAttributes;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, HasUuid, SoftDeletes, HasBilingualAttributes;

    protected $fillable = [
        'uuid',
        'parent_id',
        'slug',
        'name',
        'name_bn',
        'description',
        'description_bn',
        'hero_image',
        'icon_url',
        'seo_meta',
        'layout_json',
        'sort_order',
    ];

    protected $casts = [
        'seo_meta' => 'array',
        'layout_json' => 'array',
        'sort_order' => 'integer',
    ];

    /**
     * @return BelongsTo<Category, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return HasMany<Category, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * @return HasMany<Product, $this>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
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
