<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMetadata extends Model
{
    protected $table = 'seo_metadata';

    protected $fillable = [
        'seoable_type',
        'seoable_id',
        'meta_title',
        'meta_description',
        'canonical_url',
        'og_image_url',
        'schema_type',
        'robots',
        'focus_keywords',
        'is_indexable',
    ];

    protected $casts = [
        'focus_keywords' => 'array',
        'is_indexable' => 'boolean',
    ];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
