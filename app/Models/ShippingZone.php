<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country_code',
        'regions',
        'is_active',
    ];

    protected $casts = [
        'regions' => 'array',
        'is_active' => 'boolean',
    ];

    public function rates(): HasMany
    {
        return $this->hasMany(ShippingRate::class);
    }
}
