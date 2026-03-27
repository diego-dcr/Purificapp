<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lot extends Model
{
    protected $table = 'lots';

    protected $fillable = [
        'id',
        'lot_number',
        'supplier',
        'quantity',
        'observations',
        'production_date',
        'expiration_date',
        'created_at',
        'updated_at',
    ];

    public function waterjugs(): HasMany
    {
        return $this->hasMany(Waterjug::class, 'lot_id');
    }
}
