<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StockMovementType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class InventoryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'product_id',
        'product_variant_id',
        'type',
        'quantity_delta',
        'previous_quantity',
        'new_quantity',
        'reference_type',
        'reference_id',
        'reason',
        'user_id',
    ];

    protected $casts = [
        'type' => StockMovementType::class,
        'quantity_delta' => 'integer',
        'previous_quantity' => 'integer',
        'new_quantity' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $log): void {
            if (empty($log->uuid)) {
                $log->uuid = (string) Str::uuid();
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
