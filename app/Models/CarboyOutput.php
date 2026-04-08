<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarboyOutput extends Model
{
    protected $table = 'carboy_outputs';

    public $timestamps = false;

    protected $fillable = [
        'output_id',
        'carboy_codebar',
        'timestamp',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function output(): BelongsTo
    {
        return $this->belongsTo(Output::class);
    }
}
