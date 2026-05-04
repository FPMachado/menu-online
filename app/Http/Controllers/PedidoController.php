<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id'    => 'required',
            'pagamento'     => 'required',
            'total'         => 'required',
            'tipo_entrega'  => 'required|in:entrega,retirada,local',
            'endereco_id'   => 'required_if:tipo_entrega,entrega',
        ]);

        $pedido = Pedido::create([
            'cliente_id'   => $request->cliente_id,
            'endereco_id'  => $request->tipo_entrega === 'entrega' ? $request->endereco_id : null,
            'pagamento'    => $request->pagamento,
            'total'        => $request->total,
            'tipo_entrega' => $request->tipo_entrega,
            'status'       => 'pendente'
        ]);

        return response()->json([
            'success' => true,
            'pedido'  => $pedido
        ]);
    }
}
