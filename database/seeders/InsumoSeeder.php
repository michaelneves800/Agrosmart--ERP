<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Insumo;

class InsumoSeeder extends Seeder
{
    public function run(): void
    {
        Insumo::create([
            'nome' => 'Ureia',
            'tipo' => 'Adubo',
            'unidade' => 'kg'
        ]);
    }
}
