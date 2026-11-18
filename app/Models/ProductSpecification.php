<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasBilingualAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSpecification extends Model
{
    use HasBilingualAttributes;

    protected $fillable = [
        'product_id',
        'group_name',
        'group_name_bn',
        'attribute_name',
        'attribute_name_bn',
        'attribute_value',
        'attribute_value_bn',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
