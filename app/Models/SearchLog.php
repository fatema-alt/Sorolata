<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'query',
        'results_count',
        'user_id',
        'ip_hash',
        'session_id',
        'clicked_product_id',
        'created_at',
    ];

    protected $casts = [
        'results_count' => 'integer',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clickedProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'clicked_product_id');
    }
}
