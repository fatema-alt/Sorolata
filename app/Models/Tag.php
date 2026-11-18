<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
    ];

    protected static function booted(): void
    {
        static::creating(function (Tag $tag): void {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'taggable');
    }

    public function stories(): MorphToMany
    {
        return $this->morphedByMany(Story::class, 'taggable');
    }
}
