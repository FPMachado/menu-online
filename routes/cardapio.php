<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardapioController;

Route::get('/cardapio', [CardapioController::class, 'index']);
