<?php

namespace App\Http\Controllers;

use App\Models\Ciclo;
use App\Models\Colheita;
use App\Models\Estoque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Importante para transações seguras

class ColheitaController extends Controller
{
    public function store(Request $request, $cicloId)
    {
        // 1. Validação dos dados que vêm do formulário
        $validated = $request->validate([
            'quantidade_colhida' => 'required|numeric|min:0.1', // Em Sacas ou KG
            'data_colheita' => 'required|date',
            'umidade' => 'nullable|numeric|between:0,100',
            'finalizar_ciclo' => 'boolean' // Checkbox: "Acabei de colher tudo"
        ]);

        // DB::transaction garante que ou salva tudo (colheita + estoque) ou não salva nada se der erro
        return DB::transaction(function () use ($validated, $cicloId, $request) {
            
            $ciclo = Ciclo::with('talhao')->findOrFail($cicloId);
            
            // 2. Cria o registro histórico da colheita
            Colheita::create([
                'ciclo_id' => $cicloId,
                'quantidade_colhida' => $validated['quantidade_colhida'],
                'data_colheita' => $validated['data_colheita'],
                'umidade' => $validated['umidade'] ?? 0,
            ]);

            // 3. Atualiza o Silo Virtual (Estoque) da Fazenda
            // Procura se já tem soja/milho no estoque dessa fazenda. Se não tiver, cria.
            $estoque = Estoque::firstOrCreate(
                [
                    'fazenda_id' => $ciclo->talhao->fazenda_id,
                    'cultura' => $ciclo->cultura
                ],
                ['quantidade_kg' => 0, 'unidade_medida' => 'sc'] // Vamos padronizar em Sacas (sc) por enquanto
            );
            
            // Soma o que acabou de colher ao saldo do estoque
            $estoque->increment('quantidade_kg', $validated['quantidade_colhida']);

            // 4. Se o usuário marcou "Finalizar Ciclo", liberamos o talhão para o próximo plantio
            if ($request->boolean('finalizar_ciclo')) {
                $ciclo->update(['status' => 'colhido']);
            }

            return back()->with('message', '🚜 Carga registrada! Estoque atualizado com sucesso.');
        });
    }
}