<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOrigin extends Model
{
    protected $fillable = [
        'product_id',
        'district_id',
        'artisan_id',
        'workshop_id',
        'craft_type_id',
        'gi_certificate_number',
        'harvest_season',
        'provenance_story',
        'geo_coordinates',
    ];

    protected $casts = [
        'geo_coordinates' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function artisan(): BelongsTo
    {
        return $this->belongsTo(Artisan::class);
    }

    public function workshop(): BelongsTo
    {
        return $this->belongsTo(Workshop::class);
    }

    public function craftType(): BelongsTo
    {
        return $this->belongsTo(CraftType::class);
    }
}
