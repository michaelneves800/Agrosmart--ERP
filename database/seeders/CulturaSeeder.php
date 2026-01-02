<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Talhao;
use App\Models\Cultura;

class CulturaSeeder extends Seeder
{
    public function run(): void
    {
        $talhao = Talhao::first();

        Cultura::create([
            'nome' => 'Soja',
            'talhao_id' => $talhao->id,
            'safra' => '2024/2025',
            'area_ha' => 30.5,
        ]);

        Cultura::create([
            'nome' => 'Milho',
            'talhao_id' => $talhao->id,
            'safra' => '2024/2025',
            'area_ha' => 18.0,
        ]);
    }
}
