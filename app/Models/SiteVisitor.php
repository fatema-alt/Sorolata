<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteVisitor extends Model
{
    protected $fillable = [
        'ip_hash',
        'user_agent',
        'device_type',
        'country_code',
        'city',
        'first_visit_at',
        'last_visit_at',
    ];

    protected $casts = [
        'first_visit_at' => 'datetime',
        'last_visit_at' => 'datetime',
    ];
}
