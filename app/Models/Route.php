<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Route extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'zone',
        'code',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
