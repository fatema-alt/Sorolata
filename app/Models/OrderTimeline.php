<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderTimeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'actor_type',
        'actor_id',
        'note',
        'customer_notified',
        'metadata',
    ];

    protected $casts = [
        'customer_notified' => 'boolean',
        'metadata' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
