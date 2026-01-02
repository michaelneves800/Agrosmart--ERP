<?php

namespace App\Http\Controllers;

use App\Models\Fazenda;
use App\Models\Insumo;
use Illuminate\Http\Request;

class InsumoController extends Controller
{
    public function store(Request $request, $fazendaId)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'categoria' => 'required|string',
            'quantidade' => 'required|numeric|min:0',
            'unidade' => 'required|string|max:10',
            'custo_unitario' => 'nullable|numeric|min:0',
        ]);

        $fazenda = Fazenda::findOrFail($fazendaId);

        // Verifica se o produto já existe no estoque para somar (ou cria novo)
        $insumo = Insumo::where('fazenda_id', $fazendaId)
            ->where('nome', $validated['nome'])
            ->first();

        if ($insumo) {
            // Lógica de Preço Médio (Simples):
            // Novo Custo = ((Qtd Atual * Custo Atual) + (Qtd Nova * Custo Novo)) / Qtd Total
            $custoAtualTotal = $insumo->quantidade * $insumo->custo_medio;
            $custoNovoTotal = $validated['quantidade'] * ($validated['custo_unitario'] ?? 0);
            $novaQuantidade = $insumo->quantidade + $validated['quantidade'];
            
            if ($novaQuantidade > 0) {
                $insumo->custo_medio = ($custoAtualTotal + $custoNovoTotal) / $novaQuantidade;
            }
            
            $insumo->quantidade = $novaQuantidade;
            $insumo->save();
        } else {
            // Cria do zero
            Insumo::create([
                'fazenda_id' => $fazendaId,
                'nome' => $validated['nome'],
                'categoria' => $validated['categoria'],
                'quantidade' => $validated['quantidade'],
                'unidade' => $validated['unidade'],
                'custo_medio' => $validated['custo_unitario'] ?? 0
            ]);
        }

        return back()->with('message', '📦 Produto adicionado ao Almoxarifado!');
    }
    public function show($id)
    {
        // Carrega a fazenda, os talhões (com ciclos) E AGORA OS INSUMOS
        $fazenda = Fazenda::with(['talhoes.ciclos', 'insumos'])
            ->findOrFail($id);

        return Inertia::render('Fazendas/Show', [
            'fazenda' => $fazenda
        ]);
    }
}