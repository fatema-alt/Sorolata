<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'avatar_url',
        'phone_verified_at',
        'date_of_birth',
        'gender',
        'preferred_currency',
        'preferred_locale',
        'loyalty_points',
        'membership_tier',
        'total_spent',
        'orders_count',
    ];

    protected $casts = [
        'phone_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'loyalty_points' => 'integer',
        'total_spent' => 'decimal:2',
        'orders_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
