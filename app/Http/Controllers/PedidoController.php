<?php

namespace App\Http\Controllers;

use App\Mail\PedidoConfirmadoCliente;
use App\Mail\PedidoConfirmadoRestaurante;
use App\Models\Pedido;
use App\Models\PedidoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PedidoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id'   => 'required',
            'pagamento'    => 'required',
            'total'        => 'required',
            'tipo_entrega' => 'required|in:entrega,retirada,local',
            'endereco_id'  => 'required_if:tipo_entrega,entrega',
            'itens'        => 'required|array|min:1',
            'itens.*.produto_id' => 'required|exists:produtos,id',
            'itens.*.nome'       => 'required|string',
            'itens.*.preco'      => 'required|numeric',
            'itens.*.quantidade' => 'required|integer|min:1',
            'itens.*.subtotal'   => 'required|numeric',
        ]);

        $pedido = Pedido::create([
            'cliente_id'   => $request->cliente_id,
            'endereco_id'  => $request->tipo_entrega === 'entrega' ? $request->endereco_id : null,
            'pagamento'    => $request->pagamento,
            'total'        => $request->total,
            'tipo_entrega' => $request->tipo_entrega,
            'status'       => 'pendente',
        ]);

        foreach ($request->itens as $item) {
            PedidoItem::create([
                'pedido_id'  => $pedido->id,
                'produto_id' => $item['produto_id'],
                'nome'       => $item['nome'],
                'preco'      => $item['preco'],
                'quantidade' => $item['quantidade'],
                'subtotal'   => $item['subtotal'],
            ]);
        }

        $pedido->load('cliente', 'endereco', 'itens');

        Mail::to($pedido->cliente->email ?? 'cliente@teste.com')
            ->send(new PedidoConfirmadoCliente($pedido));

        Mail::to('restaurante@teste.com')
            ->send(new PedidoConfirmadoRestaurante($pedido));

        return response()->json([
            'success' => true,
            'pedido'  => $pedido,
        ]);
    }
}
