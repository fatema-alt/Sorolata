<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AdminNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'type',
        'title',
        'message',
        'action_url',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $notification): void {
            if (empty($notification->uuid)) {
                $notification->uuid = (string) Str::uuid();
            }
        });
    }

    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'order' => 'Order',
            'payment' => 'Payment Verification',
            'cancellation' => 'Cancellation',
            'stock' => 'Stock Alert',
            'review' => 'Customer Review',
            default => ucfirst((string) $this->type),
        };
    }

    public function getTypeColor(): string
    {
        return match ($this->type) {
            'order' => 'emerald',
            'payment' => 'blue',
            'cancellation' => 'rose',
            'stock' => 'amber',
            'review' => 'purple',
            default => 'gray',
        };
    }
}
