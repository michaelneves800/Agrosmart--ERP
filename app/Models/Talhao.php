<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talhao extends Model
{
    use HasFactory;

    protected $table = 'talhoes';

    protected $fillable = [
        'fazenda_id',
        'nome',
        'area_hectares',
        'tipo_solo'
    ];

    // Relacionamento com Fazenda (já deve existir)
    public function fazenda()
    {
        return $this->belongsTo(Fazenda::class);
    }

    // ADICIONE ESTE BLOCO NOVO:
    public function ciclos()
    {
        return $this->hasMany(Ciclo::class);
    }
}