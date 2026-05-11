<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuracao;
use Illuminate\Http\Request;

class AdminConfiguracaoController extends Controller
{
    public function index()
    {
        $config = Configuracao::first();

        if (!$config) {
            $config = Configuracao::create([]);
        }

        return response()->json([
            'configuracao' => $config,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'nome_restaurante'  => 'required|string',
            'chave_pix'         => 'nullable|string',
            'email_notificacao' => 'nullable|email',
            'taxa_entrega'      => 'nullable|numeric',
            'aberto'            => 'boolean',
            'horario_abertura'  => 'nullable|string',
            'horario_fechamento'=> 'nullable|string',
            'tempo_entrega'     => 'nullable|string',
        ]);

        $config = Configuracao::first();

        if (!$config) {
            $config = Configuracao::create($request->all());
        } else {
            $config->update($request->all());
        }

        return response()->json([
            'success'      => true,
            'configuracao' => $config,
        ]);
    }
}
