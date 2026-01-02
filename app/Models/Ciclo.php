<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciclo extends Model
{
    protected $fillable = [
    'talhao_id', 'cultura', 'variedade', 'data_plantio', 'estimativa_colheita', 'status'
];

// Aproveite para criar a relação inversa
public function talhao() {
    return $this->belongsTo(Talhao::class);
}
// Relacionamento com as colheitas
    public function colheitas()
    {
        return $this->hasMany(Colheita::class);
    }
}
