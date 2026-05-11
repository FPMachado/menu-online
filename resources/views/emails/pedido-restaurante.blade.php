@extends('emails.layout')

@section('content')
    <div class="header">
        <h1>🛵 Novo Pedido!</h1>
        <p>Um novo pedido acabou de chegar</p>
    </div>

    <div class="body">
        <span class="badge">Pedido #{{ $pedido->id }}</span>

        <div class="info-box">
            <div class="info-row">
                <span>Cliente</span>
                <span>{{ $pedido->cliente->nome }}</span>
            </div>
            <div class="info-row">
                <span>WhatsApp</span>
                <span>{{ $pedido->cliente->telefone }}</span>
            </div>
            <div class="info-row">
                <span>Pagamento</span>
                <span>{{ $pedido->pagamento }}</span>
            </div>
            <div class="info-row">
                <span>Tipo</span>
                <span>{{ ucfirst($pedido->tipo_entrega) }}</span>
            </div>
        </div>

        @if($pedido->endereco)
        <div class="info-box">
            <div class="info-row">
                <span>Rua</span>
                <span>{{ $pedido->endereco->rua }}, {{ $pedido->endereco->numero }}</span>
            </div>
            <div class="info-row">
                <span>Bairro</span>
                <span>{{ $pedido->endereco->bairro }}</span>
            </div>
            <div class="info-row">
                <span>Cidade</span>
                <span>{{ $pedido->endereco->cidade }}</span>
            </div>
            @if($pedido->endereco->complemento)
            <div class="info-row">
                <span>Complemento</span>
                <span>{{ $pedido->endereco->complemento }}</span>
            </div>
            @endif
            @if($pedido->endereco->referencia)
            <div class="info-row">
                <span>Referência</span>
                <span>{{ $pedido->endereco->referencia }}</span>
            </div>
            @endif
        </div>
        @endif

        @if($pedido->itens->count() > 0)
            <div class="info-box">
                <p style="font-size: 13px; font-weight: 700; margin-bottom: 12px;">Itens do pedido</p>
                @foreach($pedido->itens as $item)
                <div class="info-row">
                    <span>{{ $item->quantidade }}x {{ $item->nome }}</span>
                    <span>R$ {{ number_format($item->subtotal, 2, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
        @endif

        <div class="total-box">
            <span>Total do pedido</span>
            <span>R$ {{ number_format($pedido->total, 2, ',', '.') }}</span>
        </div>
    </div>
@endsection
