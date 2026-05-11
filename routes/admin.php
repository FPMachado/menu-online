<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminPedidoController;
use App\Http\Controllers\Admin\AdminCardapioController;
use App\Http\Controllers\Admin\AdminConfiguracaoController;
use App\Http\Controllers\Admin\UploadController;

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {

    // PEDIDOS
    Route::get('/pedidos', [AdminPedidoController::class, 'index']);
    Route::patch('/pedidos/{pedido}/status', [AdminPedidoController::class, 'atualizarStatus']);

    // CATEGORIAS
    Route::get('/categorias', [AdminCardapioController::class, 'getCategorias']);
    Route::post('/categorias', [AdminCardapioController::class, 'storeCategoria']);
    Route::put('/categorias/{categoria}', [AdminCardapioController::class, 'updateCategoria']);
    Route::delete('/categorias/{categoria}', [AdminCardapioController::class, 'destroyCategoria']);

    // PRODUTOS
    Route::get('/produtos', [AdminCardapioController::class, 'getProdutos']);
    Route::post('/produtos', [AdminCardapioController::class, 'storeProduto']);
    Route::put('/produtos/{produto}', [AdminCardapioController::class, 'updateProduto']);
    Route::delete('/produtos/{produto}', [AdminCardapioController::class, 'destroyProduto']);

    //CONFIGURACOES
    Route::get('/configuracoes', [AdminConfiguracaoController::class, 'index']);
    Route::put('/configuracoes', [AdminConfiguracaoController::class, 'update']);

    Route::post('/upload', [UploadController::class, 'store']);

    Route::patch('/produtos/{produto}/esgotado', [AdminCardapioController::class, 'toggleEsgotado']);
});
