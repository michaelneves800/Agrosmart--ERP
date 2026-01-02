<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manutencao extends Model
{
    use HasFactory;

    // Precisamos definir o nome da tabela pois 'manutencoes' foge um pouco do padrão inglês
    protected $table = 'manutencoes';

    protected $fillable = [
        'maquina_id', 'descricao', 'data_manutencao', 
        'custo', 'horimetro_na_data', 'tipo'
    ];
    
    protected $casts = ['data_manutencao' => 'date'];

    public function maquina()
    {
        return $this->belongsTo(Maquina::class);
    }
}