<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

class AdminPedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with('cliente', 'endereco', 'itens')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'pedidos' => $pedidos,
        ]);
    }

    public function atualizarStatus(Request $request, Pedido $pedido)
    {
        $request->validate([
            'status' => 'required|in:pendente,confirmado,em_entrega,pronto,entregue,cancelado',
        ]);

        $pedido->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'pedido'  => $pedido,
        ]);
    }
}
