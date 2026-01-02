<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;
use App\Models\Talhao;

class Fazenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'nome',
        'cidade',
        'area_total',
        'localizacao'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function talhoes()
    {
        return $this->hasMany(Talhao::class);
    }
    // Relacionamento com Insumos (Almoxarifado)
    public function insumos()
    {
        return $this->hasMany(Insumo::class);
    }
}
