<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\Fazenda;

class FazendaSeeder extends Seeder
{
    public function run(): void
    {
        $empresa = Empresa::first();

        Fazenda::create([
            'empresa_id' => $empresa->id,
            'nome' => 'Fazenda São João',
            'area_total_ha' => 150.75,
            'municipio' => 'Cascavel',
            'estado' => 'PR'
        ]);
    }
}
