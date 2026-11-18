<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'product_id',
        'version',
        'layout_json',
        'change_summary',
        'created_by',
        'is_autosave',
    ];

    protected $casts = [
        'version' => 'integer',
        'layout_json' => 'array',
        'is_autosave' => 'boolean',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
