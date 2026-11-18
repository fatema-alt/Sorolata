<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DistrictRegion;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class District extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'uuid',
        'slug',
        'name_en',
        'name_bn',
        'division',
        'gi_specialty',
        'historical_narrative',
        'river_basin',
        'latitude',
        'longitude',
        'meta',
    ];

    protected static function booted(): void
    {
        static::creating(function (District $district): void {
            if (empty($district->slug) && !empty($district->name_en)) {
                $district->slug = static::generateUniqueSlug($district->name_en);
            }
        });
    }

    public static function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        if (empty($base)) {
            $base = 'district-' . Str::lower(Str::random(6));
        }
        $slug = $base;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSlugAttribute(?string $value): string
    {
        if (!empty($value)) {
            return $value;
        }

        return Str::slug($this->name_en ?? (string) $this->uuid);
    }

    protected $casts = [
        'division' => DistrictRegion::class,
        'latitude' => 'float',
        'longitude' => 'float',
        'meta' => 'array',
    ];

    /**
     * @return HasMany<Artisan, $this>
     */
    public function artisans(): HasMany
    {
        return $this->hasMany(Artisan::class);
    }

    /**
     * @return HasMany<Product, $this>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function divisionRel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function workshops(): HasMany
    {
        return $this->hasMany(Workshop::class);
    }

    public function craftTypes(): HasMany
    {
        return $this->hasMany(CraftType::class, 'origin_district_id');
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
