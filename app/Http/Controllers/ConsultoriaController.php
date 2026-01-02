<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Insumo;
use App\Models\Ciclo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ConsultoriaController extends Controller {

    public function pragas() {
    $insumos = \App\Models\Insumo::all(['nome', 'quantidade'])->toArray();
    return inertia('Pragas/Index', ['insumosEmEstoque' => $insumos]);
}

public function analisarPraga(Request $request) {
    // Envia para o seu serviço Python na porta 5000
    $response = Http::post('http://127.0.0.1:5000/analisar-praga', [
        'sintomas' => $request->sintomas,
        'insumos' => $request->insumos
    ]);
    return response()->json($response->json());
}
    public function index() {
        // Busca o faturamento acumulado real do seu dashboard
        $faturamentoTotal = Ciclo::where('status', 'colhido')
            ->sum(DB::raw('quantidade_colhida * preco_venda_saca')) ?? 0;

        $dados = [
            'faturamento' => number_format($faturamentoTotal, 2, ',', '.'),
            'area_total' => '675.00', // Sua área real
            'clima' => '24°C em Curiuva',
            'estoque' => Insumo::all(['nome', 'quantidade'])->toArray()
        ];
        return Inertia::render('Consultoria/Index', ['dadosFazenda' => $dados]);
    }

    public function gerarRelatorio(Request $request) {
        // Chama o microserviço Python na porta 5000
        $response = Http::post('http://127.0.0.1:5000/gerar-relatorio', $request->all());
        return response()->json($response->json());
    }
}