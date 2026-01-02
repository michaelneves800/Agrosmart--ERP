<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maquina extends Model
{
    use HasFactory;

    protected $fillable = [
        'fazenda_id', 'nome', 'tipo', 'modelo', 
        'ano_fabricacao', 'horimetro_atual', 'status'
    ];

    public function fazenda()
    {
        return $this->belongsTo(Fazenda::class);
    }
    public function manutencoes()
    {
        return $this->hasMany(Manutencao::class);
    }
}