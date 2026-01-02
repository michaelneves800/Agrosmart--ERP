<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cultura extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'talhao_id',
        'safra',
        'area_ha',
    ];
}
