<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaterjugOutput extends Model
{
    protected $table = 'waterjug_outputs';

    public $timestamps = false;

    protected $fillable = [
        'output_id',
        'waterjug_codebar',
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
