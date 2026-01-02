<?php

namespace App\Http\Controllers;

use App\Models\Talhao;
use App\Models\Ciclo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\AgroIA;

class CicloController extends Controller
{
    // Tela de Plantio
    public function create($talhaoId)
    {
        $talhao = Talhao::with('fazenda')->findOrFail($talhaoId);
        return Inertia::render('Ciclos/Create', ['talhao' => $talhao]);
    }

    // Salvar Plantio
    public function store(Request $request, $talhaoId)
    {
        $validated = $request->validate([
            'cultura' => 'required|string',
            'variedade' => 'required|string',
            'data_plantio' => 'required|date',
        ]);

        Ciclo::create([
            'talhao_id' => $talhaoId,
            'cultura' => $validated['cultura'],
            'variedade' => $validated['variedade'],
            'data_plantio' => $validated['data_plantio'],
            'status' => 'plantado'
        ]);

        $talhao = Talhao::find($talhaoId);
        return to_route('fazendas.show', $talhao->fazenda_id);
    }

    // IA / Previsão
    public function prever($id)
    {
        $ciclo = Ciclo::with('talhao')->findOrFail($id);
        
        // Instancia a IA Real (AgroIA Service)
        $iaService = new AgroIA();
        $resultado = $iaService->estimarColheita($ciclo);

        return back()->with('previsao', $resultado);
    }

 public function colher(Request $request, $id)
{
    $validated = $request->validate([
        'quantidade_colhida' => 'required|numeric|min:0.01',
        'data_colheita' => 'required|date',
        'umidade' => 'nullable|numeric',
        'finalizar_ciclo' => 'boolean'
        
    ]);

    $ciclo = Ciclo::with('talhao')->findOrFail($id);

    // Atualiza os dados de colheita
    $ciclo->quantidade_colhida = $validated['quantidade_colhida'];
    $ciclo->data_colheita = $validated['data_colheita'];
    $ciclo->umidade = $validated['umidade'] ?? null;
    $ciclo->data_fim = $validated['data_colheita']; // Campo novo da migration
    $ciclo->preco_venda_saca = $request->preco_venda_saca;


    // Cálculo automático de produtividade (sc/ha)
    if ($ciclo->talhao->area_hectares > 0) {
        $ciclo->produtividade_real = $validated['quantidade_colhida'] / $ciclo->talhao->area_hectares;
    }

    // Se finalizar, ele "some" do talhão e aparece no histórico
    if ($request->finalizar_ciclo) {
        $ciclo->status = 'colhido';
    }

    $ciclo->save();

    return back()->with('message', 'Colheita registrada com sucesso! 🚜');
}
}