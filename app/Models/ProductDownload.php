<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDownload extends Model
{
    protected $fillable = [
        'product_id',
        'title',
        'file_url',
        'file_size',
        'download_limit',
        'expires_at',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'download_limit' => 'integer',
        'expires_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
