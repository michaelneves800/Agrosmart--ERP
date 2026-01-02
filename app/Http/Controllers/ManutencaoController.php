<?php

namespace App\Http\Controllers;

use App\Models\Maquina;
use App\Models\Manutencao;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ManutencaoController extends Controller
{
    public function index()
    {
        // Lista as últimas manutenções com o nome da máquina
        $manutencoes = Manutencao::with('maquina')
            ->orderBy('data_manutencao', 'desc')
            ->get();

        $maquinas = Maquina::all(); // Para o select do formulário

        return Inertia::render('Maquinas/Manutencoes', [
            'manutencoes' => $manutencoes,
            'maquinas' => $maquinas
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'maquina_id' => 'required|exists:maquinas,id',
            'descricao' => 'required|string',
            'data_manutencao' => 'required|date',
            'custo' => 'required|numeric|min:0',
            'tipo' => 'required|string'
        ]);

        Manutencao::create($validated);

        // Opcional: Atualizar status da máquina se for corretiva (quebrada)
        if ($request->tipo === 'corretiva') {
            $maquina = Maquina::find($request->maquina_id);
            $maquina->status = 'manutencao';
            $maquina->save();
        }

        return back()->with('message', '🔧 Manutenção registrada!');
    }
}