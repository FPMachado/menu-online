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

    public function store(Request $request)
    {
        $request->validate([
            'nome'     => 'required|string|min:3',
            'telefone' => 'required|string',
        ]);

        $telefone = preg_replace('/\D/', '', $request->telefone);

        $cliente = Cliente::firstOrCreate(
            ['telefone' => $telefone],
            ['nome' => $request->nome]
        );

        return response()->json([
            'success' => true,
            'cliente' => $cliente
        ]);
    }

    public function storeEndereco(Request $request, Cliente $cliente)
    {
        $request->validate([
            'rua'    => 'required|string',
            'numero' => 'required|string',
            'bairro' => 'required|string',
        ]);

        $endereco = $cliente->enderecos()->firstOrCreate(
            [
                'rua'    => $request->rua,
                'numero' => $request->numero,
                'bairro' => $request->bairro,
                'cidade' => $request->cidade,
            ],
            [
                'cep'         => $request->cep,
                'complemento' => $request->complemento,
                'referencia'  => $request->referencia,
            ]
        );

        return response()->json([
            'success'  => true,
            'endereco' => $endereco
        ]);
    }
}
