<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    protected $fillable = [
        'name',
        'bn_name',
        'slug',
        'coordinates',
    ];

    protected $casts = [
        'coordinates' => 'array',
    ];

    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }
}
