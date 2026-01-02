<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Safra;

class SafraSeeder extends Seeder
{
    public function run(): void
    {
        Safra::create([
            'nome' => 'Safra 2024/2025',
            'ano_inicio' => 2024,
            'ano_fim' => 2025,
        ]);
        
        Safra::create([
            'nome' => 'Safra 2023/2024',
            'ano_inicio' => 2023,
            'ano_fim' => 2024,
        ]);
    }
}