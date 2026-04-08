<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemIncome extends Model
{
    protected $table = 'incomes';

    public $timestamps = false;

    protected $fillable = [
        'concept_id',
        'customer_id',
        'user_id',
        'description',
        'amount',
        'created_by',
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
