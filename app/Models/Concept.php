<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concept extends Model
{
    use HasFactory;

    protected $table = 'concepts';

    public const TYPE_INCOME = 'income';
    public const TYPE_EXPENSE = 'expense';
    public const TYPE_NONE = 'none';
    
    protected $fillable = [
        'name',
        'code',
        'type',
        'allows_carboy',
    ];

    protected $casts = [
        'allows_carboy' => 'bool',
    ];

    public static function movementTypes(): array
    {
        return [
            self::TYPE_INCOME => 'Ingreso',
            self::TYPE_EXPENSE => 'Egreso',
            self::TYPE_NONE => 'Sin movimiento',
        ];
    }
}
