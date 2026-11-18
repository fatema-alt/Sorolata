<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CraftType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'bn_name',
        'slug',
        'description',
        'origin_district_id',
        'is_gi_certified',
        'icon',
    ];

    protected $casts = [
        'is_gi_certified' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (CraftType $craft): void {
            if (empty($craft->uuid)) {
                $craft->uuid = (string) Str::uuid();
            }
            if (empty($craft->slug)) {
                $craft->slug = Str::slug($craft->name);
            }
        });
    }

    public function originDistrict(): BelongsTo
    {
        return $this->belongsTo(District::class, 'origin_district_id');
    }

    public function productOrigins(): HasMany
    {
        return $this->hasMany(ProductOrigin::class);
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }
}
