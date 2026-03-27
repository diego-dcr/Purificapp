<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'route_id',
        'barcode',
        'number',
        'name',
    ];

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }
}
