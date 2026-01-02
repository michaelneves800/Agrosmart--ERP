<?php

namespace App\Http\Controllers;

use App\Models\Fazenda;
use App\Models\Ciclo; // Certifique-se de que o Model Ciclo está importado
use Illuminate\Http\Request;
use Inertia\Inertia;

class FazendaController extends Controller
{
    public function index()
    {
        return Inertia::render('Fazendas/Index', [
            'fazendas' => Fazenda::all()
        ]);
    }

    public function show($id)
    {
        // 1. Carrega a fazenda com talhões e apenas os ciclos que estão PLANTADOS (Safra Ativa)
        $fazenda = Fazenda::with(['talhoes.ciclos' => function($query) {
            $query->where('status', 'plantado');
        }, 'insumos'])->findOrFail($id);
        
        // 2. Busca o histórico de tudo que já foi COLHIDO nesta fazenda para a Timeline
        $historico = Ciclo::whereIn('talhao_id', $fazenda->talhoes->pluck('id'))
            ->where('status', 'colhido')
            ->orderBy('data_colheita', 'desc')
            ->get();
        
        return Inertia::render('Fazendas/Show', [
            'fazenda' => $fazenda,
            'historico' => $historico, // Enviamos a lista para a nova Timeline
            'previsao' => session('previsao')
        ]);
    }
    
    public function create()
    {
        return Inertia::render('Fazendas/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:2',
        ]);

        Fazenda::create($validated);

        return to_route('dashboard')->with('message', 'Fazenda criada com sucesso! 🚜');
    }

    public function edit(Fazenda $fazenda)
    {
        return inertia('Fazendas/Edit', [
            'fazenda' => $fazenda
        ]);
    }

    public function update(Request $request, Fazenda $fazenda)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:2',
        ]);

        $fazenda->update($validated);

        return redirect()->route('dashboard')->with('message', 'Fazenda atualizada com sucesso! ✏️');
    }

    public function destroy(Fazenda $fazenda)
{
    // Removida a trava de autorização que causava o erro 500
    // Removida a trava de talhões existentes para permitir a limpeza completa

    try {
        // Opcional: Se não houver configuração de 'onDelete cascade' no Banco de Dados, 
        // fazemos manualmente aqui para garantir a limpeza total:
        $fazenda->talhoes()->each(function($talhao) {
            $talhao->ciclos()->delete(); // Apaga ciclos do talhão
            $talhao->delete();           // Apaga o talhão
        });

        $fazenda->delete(); // Por fim, apaga a fazenda

        return redirect()->route('dashboard')->with('message', 'Fazenda e todos os dados vinculados foram removidos! 🗑️');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Erro ao tentar excluir: ' . $e->getMessage());
    }
}
}