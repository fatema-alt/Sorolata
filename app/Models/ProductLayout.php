<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductLayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'version',
        'block_payload',
        'is_published',
        'change_summary',
        'created_by',
    ];

    protected $casts = [
        'version' => 'integer',
        'block_payload' => 'array',
        'is_published' => 'boolean',
    ];

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
