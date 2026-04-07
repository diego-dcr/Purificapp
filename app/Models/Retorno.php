<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Retorno extends Model
{
    protected $table = 'retornos';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'route_id',
        'created_by',
        'external_id',
        'latitude',
        'longitude',
        'timestamp',
    ];

    protected $casts = [
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

    public function carboyRetornos(): HasMany
    {
        return $this->hasMany(CarboyOutput::class, 'retorno_id');
    }
}
