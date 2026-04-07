<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarboyOutput extends Model
{
    protected $table = 'carboy_retornos';

    public $timestamps = false;

    protected $fillable = [
        'retorno_id',
        'carboy_codebar',
        'timestamp',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function retorno(): BelongsTo
    {
        return $this->belongsTo(Retorno::class);
    }
}
