<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $table = 'expenses';

    public $timestamps = false;

    protected $fillable = [
        'concept_id',
        'amount',
        'description',
        'timestamp',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'timestamp' => 'datetime',
    ];

    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class);
    }
}
