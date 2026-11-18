<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ArtisanCraftType;
use App\Traits\HasBilingualAttributes;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Artisan extends Model
{
    use HasFactory, HasUuid, SoftDeletes, HasBilingualAttributes;

    protected $fillable = [
        'uuid',
        'slug',
        'district_id',
        'full_name',
        'full_name_bn',
        'atelier_name',
        'atelier_name_bn',
        'craft_type',
        'years_of_mastery',
        'biography',
        'biography_bn',
        'quote',
        'quote_bn',
        'portrait_url',
        'awards',
        'is_verified',
    ];

    protected static function booted(): void
    {
        static::creating(function (Artisan $artisan): void {
            if (empty($artisan->slug) && !empty($artisan->full_name)) {
                $artisan->slug = static::generateUniqueSlug($artisan->full_name);
            }
        });
    }

    public static function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        if (empty($base)) {
            $base = 'artisan-' . Str::lower(Str::random(6));
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

        return Str::slug($this->full_name ?? (string) $this->uuid);
    }

    protected $casts = [
        'craft_type' => ArtisanCraftType::class,
        'years_of_mastery' => 'integer',
        'awards' => 'array',
        'is_verified' => 'boolean',
    ];

    /**
     * @return BelongsTo<District, $this>
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * @return HasMany<Product, $this>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * @return MorphMany<MediaAsset, $this>
     */
    public function media(): MorphMany
    {
        return $this->morphMany(MediaAsset::class, 'attachable');
    }

    public function workshop(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Workshop::class);
    }

    public function seo(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }

    public function mediaAttachments(): MorphMany
    {
        return $this->morphMany(MediaAttachment::class, 'mediable')->orderBy('sort_order');
    }
}
