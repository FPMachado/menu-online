<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;

Route::get('/buscar-cliente', [ClienteController::class, 'buscar']);
