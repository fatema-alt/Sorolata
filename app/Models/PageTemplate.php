<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageTemplate extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'uuid',
        'name',
        'category',
        'thumbnail_url',
        'layout_json',
        'is_global',
    ];

    protected $casts = [
        'layout_json' => 'array',
        'is_global' => 'boolean',
    ];
}
