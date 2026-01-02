<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    use HasFactory;

    protected $fillable = [
        'fazenda_id', 
        'cultura', 
        'quantidade_kg', 
        'unidade_medida'
    ];

    public function fazenda()
    {
        return $this->belongsTo(Fazenda::class);
    }
}