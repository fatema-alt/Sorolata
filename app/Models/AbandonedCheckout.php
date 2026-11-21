<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbandonedCheckout extends Model
{
    use HasFactory;

    protected $table = 'abandoned_checkouts';

    protected $fillable = [
        'uuid',
        'customer_name',
        'customer_phone',
        'customer_email',
        'shipping_address',
        'district',
        'subtotal',
        'delivery_charge',
        'total_amount',
        'items_json',
        'is_recovered',
        'recovered_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'delivery_charge' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'items_json' => 'array',
        'is_recovered' => 'boolean',
        'recovered_at' => 'datetime',
    ];

    public function getItemsListAttribute(): array
    {
        if (is_array($this->items_json)) {
            return $this->items_json;
        }

        if (is_string($this->items_json)) {
            return json_decode($this->items_json, true) ?: [];
        }

        return [];
    }
}
