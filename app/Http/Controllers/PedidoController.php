<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required',
            'endereco_id' => 'nullable',
            'pagamento' => 'required',
            'total' => 'required',
        ]);

        $pedido = Pedido::create([
            'cliente_id' => $request->cliente_id,
            'endereco_id' => $request->endereco_id,
            'pagamento' => $request->pagamento,
            'total' => $request->total,
            'status' => 'pendente'
        ]);

        return response()->json([
            'success' => true,
            'pedido' => $pedido
        ]);
    }
}
