<?php

namespace App\Http\Controllers;

use App\Models\Fazenda;
use App\Models\Maquina;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MaquinaController extends Controller
{
    public function index()
    {
        // Pega todas as máquinas cadastradas no sistema
        // (Em produção, filtraríamos pela fazenda do usuário logado)
        $maquinas = Maquina::with('fazenda')->orderBy('nome')->get();
        $fazendas = Fazenda::all(); // Para o select na hora de cadastrar

        return Inertia::render('Maquinas/Index', [
            'maquinas' => $maquinas,
            'fazendas' => $fazendas
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fazenda_id' => 'required|exists:fazendas,id',
            'nome' => 'required|string',
            'tipo' => 'required|string',
            'modelo' => 'nullable|string',
            'horimetro_atual' => 'required|numeric',
        ]);

        Maquina::create($validated);

        return back()->with('message', '🚜 Máquina cadastrada na frota!');
    }
}