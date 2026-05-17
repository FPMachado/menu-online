<?php

use Illuminate\Support\Facades\Route;

require __DIR__ . '/clientes.php';
require __DIR__ . '/pedidos.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/cardapio.php';
Route::get('/configuracoes', [App\Http\Controllers\ConfiguracaoController::class, 'index']);
Route::get('/pedidos/{id}/status', [App\Http\Controllers\PedidoController::class, 'status']);
