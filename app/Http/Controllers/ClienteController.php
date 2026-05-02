<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    public function buscar(Request $request)
    {
        $telefone = preg_replace('/\D/', '', $request->telefone);

        $cliente = Cliente::with('enderecos')
            ->where('telefone', $telefone)
            ->first();

        if (!$cliente) {
            return response()->json([
                'existe' => false
            ]);
        }

        return response()->json([
            'existe' => true,
            'cliente' => [
                'id' => $cliente->id,
                'nome' => $cliente->nome,
                'telefone' => $cliente->telefone,
                'enderecos' => $cliente->enderecos->map(function ($endereco) {
                    return [
                        'id' => $endereco->id,
                        'cep' => $endereco->cep,
                        'rua' => $endereco->rua,
                        'numero' => $endereco->numero,
                        'bairro' => $endereco->bairro,
                        'cidade' => $endereco->cidade,
                        'estado' => $endereco->estado,
                        'complemento' => $endereco->complemento,
                        'referencia' => $endereco->referencia,
                        'principal' => $endereco->principal,
                    ];
                })->values()
            ]
        ]);
    }
}
