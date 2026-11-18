<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_zone_id',
        'name',
        'rate_type',
        'base_rate',
        'per_kg_rate',
        'min_weight',
        'max_weight',
        'free_shipping_min_amount',
        'estimated_days',
        'is_active',
    ];

    protected $casts = [
        'base_rate' => 'float',
        'per_kg_rate' => 'float',
        'min_weight' => 'float',
        'max_weight' => 'float',
        'free_shipping_min_amount' => 'float',
        'is_active' => 'boolean',
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::class, 'shipping_zone_id');
    }

    public function calculateFee(float $weight = 0.5, float $subtotal = 0.0): float
    {
        if ($this->free_shipping_min_amount !== null && $subtotal >= $this->free_shipping_min_amount) {
            return 0.0;
        }

        if ($this->rate_type === 'flat') {
            return $this->base_rate;
        }

        if ($this->rate_type === 'weight_based') {
            $extraWeight = max(0, $weight - 1.0);
            return round($this->base_rate + ($extraWeight * $this->per_kg_rate), 2);
        }

        return $this->base_rate;
    }
}
