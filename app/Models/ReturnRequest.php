<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ReturnStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ReturnRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'order_id',
        'user_id',
        'status',
        'reason',
        'customer_note',
        'admin_note',
        'refund_amount',
        'refund_type',
        'evidence_images',
    ];

    protected $casts = [
        'status' => ReturnStatus::class,
        'refund_amount' => 'float',
        'evidence_images' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $return): void {
            if (empty($return->uuid)) {
                $return->uuid = (string) Str::uuid();
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
