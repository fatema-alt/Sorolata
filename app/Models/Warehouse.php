<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Warehouse extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'code',
        'contact_person',
        'phone',
        'email',
        'address',
        'district_id',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Warehouse $wh): void {
            if (empty($wh->uuid)) {
                $wh->uuid = (string) Str::uuid();
            }
        });
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(WarehouseStock::class);
    }

    public function adjustments(): HasMany
    {
        return $this->hasMany(StockAdjustment::class);
    }
}
