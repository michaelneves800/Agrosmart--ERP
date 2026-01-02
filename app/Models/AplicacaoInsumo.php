<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AplicacaoInsumo extends Model
{
    protected $table = 'aplicacoes_insumos'; // 👈 ISSO RESOLVE TUDO

    protected $fillable = [
        'insumo_id',
        'talhao_id',
        'cultura_id',
        'safra_id',
        'quantidade',
        'custo_total',
        'data_aplicacao',
    ];

    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }

    public function talhao()
    {
        return $this->belongsTo(Talhao::class);
    }

    public function cultura()
    {
        return $this->belongsTo(Cultura::class);
    }

    public function safra()
    {
        return $this->belongsTo(Safra::class);
    }
}
