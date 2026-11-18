<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UrlRedirect extends Model
{
    protected $fillable = [
        'source_url',
        'destination_url',
        'status_code',
        'hits_count',
        'is_active',
    ];

    protected $casts = [
        'status_code' => 'integer',
        'hits_count' => 'integer',
        'is_active' => 'boolean',
    ];
}
