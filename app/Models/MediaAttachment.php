<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MediaAttachment extends Model
{
    protected $fillable = [
        'media_asset_id',
        'mediable_type',
        'mediable_id',
        'role',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function mediaAsset(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class);
    }

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }
}
