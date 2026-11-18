<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignToken extends Model
{
    protected $fillable = [
        'token_key',
        'token_group',
        'token_value',
        'description',
    ];
}
