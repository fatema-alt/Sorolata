<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rate',
        'is_compound',
        'is_active',
    ];

    protected $casts = [
        'rate' => 'float',
        'is_compound' => 'boolean',
        'is_active' => 'boolean',
    ];
}
