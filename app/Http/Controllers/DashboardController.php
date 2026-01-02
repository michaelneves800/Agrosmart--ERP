<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Fazenda;
use App\Models\Talhao;
use App\Models\Ciclo;
use App\Models\Venda;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. LISTA DE FAZENDAS (Definida primeiro para evitar o erro de variável indefinida)
        $fazendas = Fazenda::withCount('talhoes')->latest()->take(4)->get();

        // 2. KPIs
        $totalFazendas = Fazenda::count();
        $totalHectares = Talhao::sum('area_hectares');
        $culturasAtivas = Ciclo::where('status', 'plantado')->distinct('cultura')->count();
        $faturamentoTotal = Venda::sum('valor_total');

        // 3. LÓGICA DE CLIMA (Baseada na primeira fazenda encontrada)
        $clima_inicial = [
            'temp' => 24,
            'condicao' => 'Ensolarado',
            'icone' => '☀️',
            'umidade' => '45%'
        ];

        // 4. GRÁFICO (Ocupação de Solo)
        $dadosGrafico = Ciclo::where('status', 'plantado')
            ->get()
            ->groupBy('cultura')
            ->map(function ($group) { return $group->count(); });

        // 5. ESTOQUE EM SILOS (Lógica Líquida)
        $colhidos = Ciclo::where('status', 'colhido')
            ->select('cultura', DB::raw('SUM(quantidade_colhida) as total'))
            ->groupBy('cultura')
            ->pluck('total', 'cultura');

        $vendidos = Venda::select('cultura', DB::raw('SUM(quantidade) as total'))
            ->groupBy('cultura')
            ->pluck('total', 'cultura');

        $estoqueReal = [];
        foreach ($colhidos as $cultura => $qtdColhida) {
            $qtdVendida = $vendidos[$cultura] ?? 0;
            $saldo = $qtdColhida - $qtdVendida;

            if ($saldo > 0) {
                $estoqueReal[] = [
                    'cultura' => $cultura,
                    'total' => $saldo
                ];
            }
        }

        // 6. RETORNO ÚNICO PARA O INERTIA
        return Inertia::render('Dashboard', [
            'kpis' => [
                'fazendas' => $totalFazendas,
                'hectares' => $totalHectares,
                'safra_ativa' => $culturasAtivas,
                'faturamento' => $faturamentoTotal
            ],
            'grafico' => [
                'labels' => $dadosGrafico->keys(),
                'data' => $dadosGrafico->values()
            ],
            'estoque' => $estoqueReal,
            'lista_fazendas' => $fazendas,
            'clima_inicial' => $clima_inicial
        ]);
    }
}