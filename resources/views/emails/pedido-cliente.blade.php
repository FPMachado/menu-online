@extends('emails.layout')

@section('content')
    <div class="header">
        <h1>🎉 Pedido Confirmado!</h1>
        <p>Seu pedido foi recebido com sucesso</p>
    </div>

    <div class="body">
        <span class="badge">Pedido #{{ $pedido->id }}</span>

        <div class="info-box">
            <div class="info-row">
                <span>Cliente</span>
                <span>{{ $pedido->cliente->nome }}</span>
            </div>
            <div class="info-row">
                <span>Pagamento</span>
                <span>{{ $pedido->pagamento }}</span>
            </div>
            <div class="info-row">
                <span>Tipo</span>
                <span>{{ ucfirst($pedido->tipo_entrega) }}</span>
            </div>
            <div class="info-row">
                <span>Status</span>
                <span>⏳ Aguardando confirmação</span>
            </div>
        </div>

        @if($pedido->endereco)
        <div class="info-box">
            <div class="info-row">
                <span>Endereço</span>
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
        </div>
        @endif

        <div class="total-box">
            <span>Total do pedido</span>
            <span>R$ {{ number_format($pedido->total, 2, ',', '.') }}</span>
        </div>

        <p style="font-size: 13px; color: #71717a; text-align: center;">
            Em breve você receberá uma atualização sobre seu pedido. Obrigado pela preferência! 🙏
        </p>
    </div>
@endsection
