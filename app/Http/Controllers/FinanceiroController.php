<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Venda;
use App\Models\Insumo;
use App\Models\Manutencao; // <--- Importante!

class FinanceiroController extends Controller
{
    // Função para exportar Vendas em CSV
public function exportarVendas()
{
    $vendas = Venda::all();
    $csvHeader = ['Data', 'Cultura', 'Comprador', 'Quantidade', 'Valor Total'];
    
    $callback = function() use ($vendas, $csvHeader) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $csvHeader);
        foreach ($vendas as $v) {
            fputcsv($file, [$v->data_venda, $v->cultura, $v->comprador, $v->quantidade, $v->valor_total]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, [
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=vendas_agro.csv",
    ]);
}
    public function index()
    {
        // 1. RECEITAS (Vendas)
        $vendas = Venda::with('fazenda')
            ->orderBy('data_venda', 'desc')
            ->get();

        // 2. DESPESAS TIPO A: Insumos
        $insumos = Insumo::with('fazenda')->get()
            ->map(function ($item) {
                return [
                    'id' => 'insumo_' . $item->id, // ID único para o loop
                    'data' => $item->created_at,
                    'titulo' => $item->nome, // Ex: Adubo
                    'subtitulo' => 'Insumo - ' . $item->categoria,
                    'valor' => $item->quantidade * $item->custo_medio,
                    'tipo' => 'insumo'
                ];
            });

        // 3. DESPESAS TIPO B: Manutenções (A NOVIDADE)
        $manutencoes = Manutencao::with('maquina')->get()
            ->map(function ($item) {
                return [
                    'id' => 'manut_' . $item->id,
                    'data' => $item->data_manutencao,
                    'titulo' => $item->descricao, // Ex: Troca de Pneu
                    'subtitulo' => 'Manutenção - ' . ($item->maquina->nome ?? 'Máquina'),
                    'valor' => $item->custo, // Custo direto
                    'tipo' => 'manutencao'
                ];
            });

        // 4. Juntar as duas despesas e ordenar por data (Mais recente primeiro)
        $todasDespesas = $insumos->concat($manutencoes)->sortByDesc('data')->values();

        // 5. Calcular Totais
        $totalReceitas = $vendas->sum('valor_total');
        $totalDespesas = $todasDespesas->sum('valor');
        $lucroLiquido = $totalReceitas - $totalDespesas;

        return Inertia::render('Financeiro/Index', [
            'extrato' => [
                'vendas' => $vendas,
                'despesas' => $todasDespesas // Enviamos a lista unificada
            ],
            'resumo' => [
                'receitas' => $totalReceitas,
                'despesas' => $totalDespesas,
                'lucro' => $lucroLiquido
            ]
        ]);
    }
}