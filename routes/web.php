<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FazendaController;
use App\Http\Controllers\TalhaoController;
use App\Http\Controllers\CicloController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\VendaController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\MaquinaController;
use App\Http\Controllers\ManutencaoController;
use Illuminate\Foundation\Application;

use Inertia\Inertia;
use App\Http\Controllers\EquipeController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsultoriaController; 


// Unifique as rotas de Consultoria e Pragas no mesmo grupo
Route::middleware(['auth'])->group(function () {
    // Consultoria
    Route::get('/consultoria', [ConsultoriaController::class, 'index'])->name('consultoria.index');
    Route::post('/consultoria/gerar', [ConsultoriaController::class, 'gerarRelatorio']);
    
    // Pragas - ESTA ROTA PRECISA ESTAR AQUI PARA O CLIQUE FUNCIONAR
    Route::get('/pragas', [ConsultoriaController::class, 'pragas'])->name('pragas');
    Route::post('/pragas/analisar', [ConsultoriaController::class, 'analisarPraga']);
});

Route::post('/ia/perguntar', [App\Http\Controllers\IAController::class, 'perguntar'])->middleware(['auth']);
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/equipe', [EquipeController::class, 'index'])->name('equipe.index');
    Route::patch('/equipe/{user}/role', [EquipeController::class, 'updateRole'])->name('equipe.updateRole');
});
Route::delete('/fazendas/{fazenda}', [FazendaController::class, 'destroy'])->name('fazendas.destroy');

Route::get('/fazendas/{fazenda}/edit', [FazendaController::class, 'edit'])->name('fazendas.edit');
Route::put('/fazendas/{fazenda}', [FazendaController::class, 'update'])->name('fazendas.update');
Route::get('/financeiro/exportar-vendas', [FinanceiroController::class, 'exportarVendas'])->name('financeiro.exportar.vendas');
Route::resource('fazendas', \App\Http\Controllers\FazendaController::class);
Route::post('/vendas', [App\Http\Controllers\VendaController::class, 'store'])->name('vendas.store');
// Adicione esta linha dentro do grupo 'auth':
Route::post('/fazendas/{id}/talhoes', [TalhaoController::class, 'store'])->name('talhoes.store');
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// --- ÁREA RESTRITA (SISTEMA COMPLETO) ---
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Fazendas
    Route::resource('fazendas', FazendaController::class);
    Route::get('/talhoes/{id}/plantar', [TalhaoController::class, 'plantar'])->name('talhoes.plantar');
    Route::post('/talhoes/{id}/plantar', [CicloController::class, 'store'])->name('ciclos.store');
    
    // Safra
    Route::post('/ciclos/{id}/prever', [CicloController::class, 'prever'])->name('ciclos.prever');
    Route::post('/ciclos/{id}/colher', [CicloController::class, 'colher'])->name('ciclos.colher');

    // Insumos
    Route::post('/fazendas/{id}/insumos', [InsumoController::class, 'store'])->name('insumos.store');

    // Financeiro
    Route::post('/vendas', [VendaController::class, 'store'])->name('vendas.store');
    Route::get('/financeiro', [FinanceiroController::class, 'index'])->name('financeiro');

    // Maquinário
    Route::get('/maquinas', [MaquinaController::class, 'index'])->name('maquinas.index');
    Route::post('/maquinas', [MaquinaController::class, 'store'])->name('maquinas.store');
    Route::get('/manutencoes', [ManutencaoController::class, 'index'])->name('manutencoes.index');
    Route::post('/manutencoes', [ManutencaoController::class, 'store'])->name('manutencoes.store');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// LOGIN E REGISTRO (Agora o arquivo existe!)
require __DIR__.'/auth.php';