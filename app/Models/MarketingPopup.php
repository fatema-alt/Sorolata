<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasBilingualAttributes;
use Illuminate\Database\Eloquent\Model;

class MarketingPopup extends Model
{
    use HasBilingualAttributes;

    protected $fillable = [
        'title',
        'title_bn',
        'trigger_type',
        'delay_seconds',
        'content',
        'content_bn',
        'is_active',
    ];

    protected $casts = [
        'delay_seconds' => 'integer',
        'content' => 'array',
        'content_bn' => 'array',
        'is_active' => 'boolean',
    ];
}
