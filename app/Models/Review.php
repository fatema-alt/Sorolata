<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'uuid',
        'product_id',
        'artisan_id',
        'user_id',
        'author_name',
        'author_location',
        'rating',
        'title',
        'content',
        'photos',
        'is_verified_purchase',
        'likes_count',
        'admin_reply',
        'admin_replied_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'photos' => 'array',
        'is_verified_purchase' => 'boolean',
        'likes_count' => 'integer',
        'admin_replied_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function artisan(): BelongsTo
    {
        return $this->belongsTo(Artisan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
