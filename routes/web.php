<?php

use App\Http\Controllers\FestaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PainelController;
use App\Http\Controllers\SorteioController;
use App\Models\Festa;

Route::get('/', function () {
    $festas = Festa::with('premios')->get();
    return view('painel.index', compact('festas'));
});

// Rotas do Painel Administrativo
Route::prefix('painel')->group(function () {
    Route::get('/', [PainelController::class, 'index'])->name('painel.index');
    Route::get('/festas/criar', [PainelController::class, 'createFesta'])->name('painel.create-festa');
    Route::post('/festas', [PainelController::class, 'storeFesta'])->name('painel.store-festa');
    Route::post('/festas/gerar', [PainelController::class, 'generateCards'])->name('painel.festa.generate-cards');
    Route::get('/festas/gerar-pdfs', [FestaController::class, 'gerarPdfs'])->name('gerar.Pdfs');
    Route::post('/festas/gerar-pdfs-lote', [FestaController::class, 'gerarPdfLote'])->name('painel.festa.generate-pdf');
});

Route::get('/festas/{festa}/pdfs', [FestaController::class, 'showPdfs'])->name('festas.showPdfs');

// Rotas da Tela de Sorteio
Route::prefix('sorteio/{festa}')->group(function () {
    Route::get('/', [SorteioController::class, 'show'])->name('sorteio.show');
    Route::post('/registrar-numero', [SorteioController::class, 'registrarNumero'])->name('sorteio.registrar-numero');
    Route::post('/remover-ultimo', [SorteioController::class, 'removerUltimoNumero'])->name('sorteio.remover-ultimo');
    Route::post('/validar-cartela', [SorteioController::class, 'validarCartela'])->name('sorteio.validar-cartela');
    Route::post('/confirmar-vencedor', [SorteioController::class, 'confirmarVencedor'])->name('sorteio.confirmar-vencedor');
});
