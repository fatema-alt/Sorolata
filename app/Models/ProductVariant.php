<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'product_id',
        'sku',
        'barcode',
        'title',
        'price',
        'compare_at_price',
        'cost_price',
        'stock_quantity',
        'reserved_stock',
        'weight',
        'attributes',
        'image_url',
        'is_active',
    ];

    protected $casts = [
        'price' => 'float',
        'compare_at_price' => 'float',
        'cost_price' => 'float',
        'stock_quantity' => 'integer',
        'reserved_stock' => 'integer',
        'weight' => 'float',
        'attributes' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $variant): void {
            if (empty($variant->uuid)) {
                $variant->uuid = (string) Str::uuid();
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function inventoryLogs(): HasMany
    {
        return $this->hasMany(InventoryLog::class, 'product_variant_id');
    }

    public function getFormattedPriceAttribute(): string
    {
        return Currency::BDT->symbol() . ' ' . number_format($this->price, 0);
    }

    public function getAvailableStockAttribute(): int
    {
        return max(0, $this->stock_quantity - $this->reserved_stock);
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->available_stock > 0;
    }
}
