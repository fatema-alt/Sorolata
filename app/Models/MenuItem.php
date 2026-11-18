<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasBilingualAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    use HasBilingualAttributes;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'title_bn',
        'bn_title',
        'label_bn',
        'url',
        'icon',
        'target',
        'sort_order',
    ];

    public function getLabelAttribute(): ?string
    {
        return $this->getLocalized('title');
    }

    public function getLocalized(string $attribute, ?string $locale = null): ?string
    {
        if ($attribute === 'label') {
            $attribute = 'title';
        }

        $locale = $locale ?? app()->getLocale();

        if ($locale === 'bn') {
            $bnValue = $this->getAttribute("{$attribute}_bn") ?? $this->getAttribute("bn_{$attribute}");
            if (!empty($bnValue)) {
                return (string) $bnValue;
            }
        }

        $val = $this->getAttribute($attribute);
        return $val !== null ? (string) $val : null;
    }

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('sort_order');
    }
}
