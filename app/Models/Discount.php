<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DiscountType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'code',
        'name',
        'type',
        'value',
        'min_subtotal',
        'max_discount_amount',
        'starts_at',
        'ends_at',
        'usage_limit',
        'used_count',
        'is_active',
        'conditions',
    ];

    protected $casts = [
        'type' => DiscountType::class,
        'value' => 'float',
        'min_subtotal' => 'float',
        'max_discount_amount' => 'float',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'is_active' => 'boolean',
        'conditions' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $discount): void {
            if (empty($discount->uuid)) {
                $discount->uuid = (string) Str::uuid();
            }
            if (!empty($discount->code)) {
                $discount->code = strtoupper(trim($discount->code));
            }
        });
    }

    public function isValidForSubtotal(float $subtotal): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->starts_at && now()->lt($this->starts_at)) {
            return false;
        }

        if ($this->ends_at && now()->gt($this->ends_at)) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        if ($subtotal < $this->min_subtotal) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if (!$this->isValidForSubtotal($subtotal)) {
            return 0.0;
        }

        $discount = match ($this->type) {
            DiscountType::PERCENTAGE => ($subtotal * ($this->value / 100)),
            DiscountType::FIXED_AMOUNT => min($subtotal, $this->value),
            DiscountType::FREE_SHIPPING => 0.0, // handled by shipping calculator
            default => 0.0,
        };

        if ($this->max_discount_amount !== null && $this->max_discount_amount > 0) {
            $discount = min($discount, $this->max_discount_amount);
        }

        return round($discount, 2);
    }
}
