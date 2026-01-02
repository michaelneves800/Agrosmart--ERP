<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;

    // AQUI ESTAVA O BLOQUEIO
    // Essa lista diz ao Laravel quais campos podem ser salvos no banco.
    protected $fillable = [
        'fazenda_id',
        'cultura',
        'quantidade',       // <--- Adicionado
        'preco_unitario',   // <--- Adicionado
        'valor_total',
        'comprador',        // <--- Adicionado
        'data_venda'        // <--- Adicionado
    ];

    public function fazenda()
    {
        return $this->belongsTo(Fazenda::class);
    }
}