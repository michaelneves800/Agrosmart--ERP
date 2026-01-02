<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Ciclo;
use Illuminate\Support\Facades\DB;

class IAController extends Controller
{
    public function perguntar(Request $request)
{
    $request->validate(['pergunta' => 'required|string']);

    try {
        $faturamento = Ciclo::where('status', 'colhido')
            ->sum(DB::raw('quantidade_colhida * preco_venda_saca')) ?? 0;

        // CORREÇÃO: Alterado de '/ask' para '/ia/perguntar' para bater com o Python
        $response = Http::post('http://127.0.0.1:5000/ia/perguntar', [
            'pergunta' => $request->pergunta,
            'faturamento' => number_format($faturamento, 2, ',', '.')
        ]);

        return response()->json($response->json());

    } catch (\Exception $e) {
        return response()->json([
            'resposta' => 'O serviço de IA em Python não respondeu. Verifique se o terminal do Python está rodando.'
        ], 500);
    }
}
}