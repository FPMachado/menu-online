<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CardapioController extends Controller
{
    public function index()
    {
        $categorias = Categoria::with(['produtos' => function ($query) {
            $query->where('ativo', true)->orderBy('ordem');
        }])
        ->where('ativo', true)
        ->orderBy('ordem')
        ->get();

        return response()->json([
            'categorias' => $categorias,
        ]);
    }
}
