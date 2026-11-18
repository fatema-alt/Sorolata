<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingPopup extends Model
{
    protected $fillable = [
        'title',
        'trigger_type',
        'delay_seconds',
        'content',
        'is_active',
    ];

    protected $casts = [
        'delay_seconds' => 'integer',
        'content' => 'array',
        'is_active' => 'boolean',
    ];
}
