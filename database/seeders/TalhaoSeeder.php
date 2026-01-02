<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Talhao;

class TalhaoSeeder extends Seeder
{
    public function run(): void
    {
        // Exemplo 1
        Talhao::create([
            'fazenda_id' => 1,
            'nome' => 'Talhão 01 - Sede',
            'area_hectares' => 50.00,  // <--- CORRIGIDO AQUI
            'tipo_solo' => 'Argiloso'
        ]);

        // Exemplo 2
        Talhao::create([
            'fazenda_id' => 1,
            'nome' => 'Talhão 02',
            'area_hectares' => 15.00,  // <--- CORRIGIDO AQUI
            'tipo_solo' => 'Arenoso'
        ]);
    }
}