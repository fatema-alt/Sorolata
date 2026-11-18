<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasBilingualAttributes;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasBilingualAttributes;

    protected $fillable = [
        'text',
        'text_bn',
        'bn_text',
        'link_url',
        'bg_color',
        'text_color',
        'is_active',
        'start_at',
        'end_at',
    ];

    public function getLocalized(string $attribute, ?string $locale = null): ?string
    {
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
        'is_active' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];
}
