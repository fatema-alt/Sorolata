<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Attribute extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'type',
        'is_filterable',
        'is_searchable',
    ];

    protected $casts = [
        'is_filterable' => 'boolean',
        'is_searchable' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Attribute $attr): void {
            if (empty($attr->uuid)) {
                $attr->uuid = (string) Str::uuid();
            }
            if (empty($attr->slug)) {
                $attr->slug = Str::slug($attr->name);
            }
        });
    }

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class)->orderBy('sort_order');
    }
}
