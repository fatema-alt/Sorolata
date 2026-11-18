<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductContent extends Model
{
    protected $fillable = [
        'product_id',
        'story_title',
        'story_narrative',
        'craft_technique',
        'heritage_significance',
        'care_guide',
        'video_embed_url',
        'builder_layout_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function builderLayout(): BelongsTo
    {
        return $this->belongsTo(ProductLayout::class, 'builder_layout_id');
    }
}
