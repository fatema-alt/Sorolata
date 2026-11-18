<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Workshop extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'artisan_id',
        'district_id',
        'name',
        'location',
        'craft_specialty',
        'founded_year',
        'workers_count',
        'description',
        'images',
        'is_active',
    ];

    protected $casts = [
        'founded_year' => 'integer',
        'workers_count' => 'integer',
        'images' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Workshop $workshop): void {
            if (empty($workshop->uuid)) {
                $workshop->uuid = (string) Str::uuid();
            }
        });
    }

    public function artisan(): BelongsTo
    {
        return $this->belongsTo(Artisan::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function productOrigins(): HasMany
    {
        return $this->hasMany(ProductOrigin::class);
    }

    public function mediaAttachments(): MorphMany
    {
        return $this->morphMany(MediaAttachment::class, 'mediable')->orderBy('sort_order');
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }
}
