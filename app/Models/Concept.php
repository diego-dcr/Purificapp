<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Concept extends Model
{
    protected $table = 'concepts';

    public const TYPE_INCOME = 'income';
    public const TYPE_EXPENSE = 'expense';
    
    protected $fillable = [
        'name',
        'code',
        'type',
    ];
}
