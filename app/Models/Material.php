<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Material extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'bn_name',
        'slug',
        'origin',
        'is_sustainable',
        'certification',
    ];

    protected $casts = [
        'is_sustainable' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Material $material): void {
            if (empty($material->uuid)) {
                $material->uuid = (string) Str::uuid();
            }
            if (empty($material->slug)) {
                $material->slug = Str::slug($material->name);
            }
        });
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_materials')
            ->withPivot('percentage');
    }
}
