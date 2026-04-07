<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarboySale extends Model
{
    protected $table = 'carboy_sales';

    public $timestamps = false;

    protected $fillable = [
        'sale_id',
        'carboy_codebar',
        'timestamp',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}
