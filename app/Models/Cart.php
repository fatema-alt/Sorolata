<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Cart extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'session_id',
        'currency',
        'total_amount',
        'coupon_code',
        'expires_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Cart $cart): void {
            if (empty($cart->uuid)) {
                $cart->uuid = (string) Str::uuid();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}
