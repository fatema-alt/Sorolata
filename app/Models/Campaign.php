<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Campaign extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'title',
        'slug',
        'banner_url',
        'start_date',
        'end_date',
        'is_active',
        'discount_type',
        'discount_value',
        'terms',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'discount_value' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Campaign $camp): void {
            if (empty($camp->uuid)) {
                $camp->uuid = (string) Str::uuid();
            }
            if (empty($camp->slug)) {
                $camp->slug = Str::slug($camp->title);
            }
        });
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }
}
