<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BuilderGlobalComponent extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'category',
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (BuilderGlobalComponent $comp): void {
            if (empty($comp->uuid)) {
                $comp->uuid = (string) Str::uuid();
            }
        });
    }
}
