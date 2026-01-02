<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venda;
use App\Models\Ciclo;

class VendaController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validação Básica
        $validated = $request->validate([
            'cultura' => 'required|string',
            'quantidade' => 'required|numeric|min:0.01',
            'preco_unitario' => 'required|numeric|min:0.01',
            'comprador' => 'required|string',
            'data_venda' => 'required|date'
        ]);

        // 2. VERIFICAÇÃO DE SALDO
        // Quanto colhemos dessa cultura?
        $totalColhido = Ciclo::where('cultura', $validated['cultura'])
            ->where('status', 'colhido')
            ->sum('quantidade_colhida');

        // Quanto já vendemos dessa cultura?
        $totalVendido = Venda::where('cultura', $validated['cultura'])
            ->sum('quantidade');

        // Saldo disponível
        $saldoDisponivel = $totalColhido - $totalVendido;

        // SE TENTAR VENDER MAIS QUE O SALDO:
        // Alterado para ->with('error') para disparar o Toast vermelho
        if ($validated['quantidade'] > $saldoDisponivel) {
            return back()->with('error', "Saldo insuficiente! Você tem {$saldoDisponivel} sc de {$validated['cultura']} disponíveis no silo.");
        }

        // 3. Se passou, registra a venda
        Venda::create([
            'fazenda_id' => $request->fazenda_id, 
            'cultura' => $validated['cultura'],
            'quantidade' => $validated['quantidade'],
            'preco_unitario' => $validated['preco_unitario'],
            'valor_total' => $validated['quantidade'] * $validated['preco_unitario'],
            'comprador' => $validated['comprador'],
            'data_venda' => $validated['data_venda']
        ]);

        // Retorna com 'message' para disparar o Toast verde de sucesso
        return back()->with('message', 'Venda realizada com sucesso! 💰');
    }
}