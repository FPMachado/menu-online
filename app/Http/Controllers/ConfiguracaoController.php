<?php

namespace App\Http\Controllers;

use App\Models\Configuracao;
use Illuminate\Http\Request;

class ConfiguracaoController extends Controller
{
    public function index()
    {
        $config = Configuracao::first();

        if (!$config) {
            $config = new Configuracao();
        }

        return response()->json([
            'configuracao' => $config,
        ]);
    }
}
