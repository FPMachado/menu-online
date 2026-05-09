<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;

Route::get('/buscar-cliente', [ClienteController::class, 'buscar']);

Route::post('/clientes', [ClienteController::class, 'store']);

Route::post('/clientes/{cliente}/enderecos', [ClienteController::class, 'storeEndereco']);
