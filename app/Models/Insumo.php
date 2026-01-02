<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
   protected $fillable = ['fazenda_id', 'nome', 'categoria', 'quantidade', 'unidade', 'custo_medio'];

    public function aplicacoes()
    {
        return $this->hasMany(AplicacaoInsumo::class);
    }
    public function fazenda()
    {
        return $this->belongsTo(Fazenda::class);
    }
}

