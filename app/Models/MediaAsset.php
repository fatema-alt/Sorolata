<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MediaAsset extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'uuid',
        'attachable_type',
        'attachable_id',
        'file_path',
        'disk',
        'mime_type',
        'file_size',
        'width',
        'height',
        'aspect_ratio',
        'blur_placeholder',
        'alt_text',
        'variants',
        'sort_order',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'variants' => 'array',
        'sort_order' => 'integer',
    ];

    /**
     * @return MorphTo<Model, $this>
     */
    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }
}
