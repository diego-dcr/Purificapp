<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $table = 'sales';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'route_id',
        'customer_id',
        'cost',
        'concept_id',
        'created_by',
        'external_id',
        'latitude',
        'longitude',
        'timestamp',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'timestamp' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class)->withDefault();
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class);
    }

    public function carboySales(): HasMany
    {
        return $this->hasMany(CarboySale::class, 'sale_id');
    }
}
