<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaterjugSale extends Model
{
    protected $table = 'waterjug_sales';

    public $timestamps = false;

    protected $fillable = [
        'input_id',
        'waterjug_codebar',
        'timestamp',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function input(): BelongsTo
    {
        return $this->belongsTo(Input::class);
    }
}
