<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyticsEvent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'session_id',
        'user_id',
        'event_name',
        'event_category',
        'event_properties',
        'url',
        'referrer',
        'created_at',
    ];

    protected $casts = [
        'event_properties' => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
