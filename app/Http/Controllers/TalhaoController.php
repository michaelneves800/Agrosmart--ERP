<?php

namespace App\Http\Controllers;

use App\Models\Talhao;
use Illuminate\Http\Request;
use Inertia\Inertia; // <--- Não esqueça de importar o Inertia

class TalhaoController extends Controller
{
    public function store(Request $request, $fazendaId)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'area_hectares' => 'required|numeric',
            'tipo_solo' => 'nullable|string'
        ]);

        Talhao::create([
            'fazenda_id' => $fazendaId,
            'nome' => $validated['nome'],
            'area_hectares' => $validated['area_hectares'],
            'tipo_solo' => $validated['tipo_solo'] ?? 'Misto'
        ]);

        return back()->with('message', 'Talhão criado com sucesso!');
    }
    
    // --- AQUI ESTAVA O PROBLEMA, AGORA ESTÁ CORRIGIDO ---
    public function plantar($id) {
        $talhao = Talhao::with('fazenda')->findOrFail($id);
        
        // Renderiza a tela de criar ciclo (safra)
        return Inertia::render('Ciclos/Create', [
            'talhao' => $talhao
        ]);
    }
}