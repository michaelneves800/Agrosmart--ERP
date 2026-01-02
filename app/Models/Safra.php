<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Safra extends Model
{
    use HasFactory;

    protected $table = 'safras';

    protected $fillable = [
        'nome',
        'ano_inicio',
        'ano_fim',
        'data_inicio',
        'data_fim'
    ];
}