<template>
    <div class="p-8" @click="inicializarAudio">

        <div class="mb-8">
            <h1 class="text-2xl font-black">Pedidos</h1>
            <p class="text-sm text-zinc-500 mt-1">Gerencie todos os pedidos</p>
        </div>

        <!-- FILTROS -->
        <div class="flex gap-3 mb-6 flex-wrap">
            <button
                v-for="filtro in filtros"
                :key="filtro.value"
                @click="filtroAtivo = filtro.value"
                class="px-4 py-2 rounded-2xl text-sm font-semibold transition border"
                :class="filtroAtivo === filtro.value
                    ? 'bg-red-500 text-white border-red-500'
                    : 'bg-white text-zinc-600 border-zinc-200 hover:bg-zinc-50'"
            >
                {{ filtro.label }}
            </button>
        </div>

        <!-- TABELA -->
        <div class="bg-white rounded-2xl border border-zinc-200">
            <div class="divide-y divide-zinc-100">
                <div
                    v-for="pedido in pedidosFiltrados"
                    :key="pedido.id"
                    :class="[
                        'p-5 flex items-start justify-between gap-4 transition',
                        pedido.status === 'pendente' ? 'bg-amber-50 border-l-4 border-amber-400' : ''
                    ]"
                >
                    <!-- INFO -->
                    <div class="flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="font-black">#{{ pedido.id }}</p>
                            <span
                                class="text-xs font-semibold px-2 py-1 rounded-full"
                                :class="{
                                    'bg-amber-100 text-amber-600': pedido.status === 'pendente',
                                    'bg-emerald-100 text-emerald-600': pedido.status === 'confirmado',
                                    'bg-blue-100 text-blue-600': pedido.status === 'em_entrega' || pedido.status === 'pronto',
                                    'bg-zinc-100 text-zinc-600': pedido.status === 'entregue',
                                    'bg-red-100 text-red-600': pedido.status === 'cancelado',
                                }"
                            >
                                {{ pedido.status.replace('_', ' ') }}
                            </span>
                        </div>
                        <p class="text-sm text-zinc-600 mt-1">{{ pedido.cliente?.nome }} • {{ pedido.cliente?.telefone }}</p>
                        <p class="text-xs text-zinc-400 mt-0.5">{{ pedido.tipo_entrega }} • {{ pedido.pagamento }}</p>

                        <button
                            @click="pedidoSelecionado = pedido"
                            class="mt-2 text-xs bg-zinc-100 hover:bg-zinc-200 text-zinc-600 font-semibold px-3 py-1.5 rounded-xl transition"
                        >
                            🔍 Ver detalhes
                        </button>
                    </div>

                    <!-- AÇÕES -->
                    <div class="text-right shrink-0">
                        <p class="font-black text-green-600 text-lg">R$ {{ pedido.total }}</p>

                        <div class="mt-2 flex flex-col gap-1 items-end">
                            <button
                                v-if="pedido.status === 'pendente'"
                                @click="atualizarStatus(pedido.id, 'confirmado')"
                                class="w-40 text-xs bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-xl font-semibold transition text-center"
                            >
                                ✅ Confirmar pedido
                            </button>

                            <button
                                v-if="pedido.status === 'confirmado' && pedido.tipo_entrega === 'entrega'"
                                @click="atualizarStatus(pedido.id, 'em_entrega')"
                                class="w-40 text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-xl font-semibold transition text-center"
                            >
                                🛵 Saiu p/ entrega
                            </button>

                            <button
                                v-if="pedido.status === 'confirmado' && pedido.tipo_entrega !== 'entrega'"
                                @click="atualizarStatus(pedido.id, 'pronto')"
                                class="w-40 text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-xl font-semibold transition text-center"
                            >
                                🍽️ Pronto p/ retirada
                            </button>

                            <button
                                v-if="pedido.status === 'em_entrega' || pedido.status === 'pronto'"
                                @click="atualizarStatus(pedido.id, 'entregue')"
                                class="w-40 text-xs bg-zinc-700 hover:bg-zinc-800 text-white px-3 py-1.5 rounded-xl font-semibold transition text-center"
                            >
                                🏁 Finalizar pedido
                            </button>

                            <button
                                v-if="pedido.status !== 'entregue' && pedido.status !== 'cancelado'"
                                @click="atualizarStatus(pedido.id, 'cancelado')"
                                class="w-40 text-xs bg-red-100 hover:bg-red-200 text-red-500 px-3 py-1.5 rounded-xl font-semibold transition text-center"
                            >
                                ✕ Cancelar pedido
                            </button>
                        </div>
                    </div>
                </div>

                <div v-if="pedidosFiltrados.length === 0" class="p-8 text-center text-zinc-400 text-sm">
                    Nenhum pedido encontrado
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DETALHES -->
    <div
        v-if="pedidoSelecionado"
        class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4"
        @click.self="pedidoSelecionado = null"
    >
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

            <div class="p-6 border-b flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-black">Pedido #{{ pedidoSelecionado.id }}</h2>
                    <p class="text-sm text-zinc-500 mt-0.5">{{ pedidoSelecionado.created_at }}</p>
                </div>
                <button
                    @click="pedidoSelecionado = null"
                    class="w-10 h-10 rounded-full bg-zinc-100 hover:bg-zinc-200"
                >
                    ✕
                </button>
            </div>

            <div class="p-6 space-y-5">

                <!-- CLIENTE -->
                <div class="bg-zinc-50 border border-zinc-200 rounded-2xl p-4 space-y-2">
                    <p class="text-sm font-black mb-2">👤 Cliente</p>
                    <div class="flex justify-between text-sm">
                        <span class="text-zinc-500">Nome</span>
                        <span class="font-semibold">{{ pedidoSelecionado.cliente?.nome }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-zinc-500">WhatsApp</span>
                        <span class="font-semibold">{{ pedidoSelecionado.cliente?.telefone }}</span>
                    </div>
                </div>

                <!-- ENTREGA -->
                <div class="bg-zinc-50 border border-zinc-200 rounded-2xl p-4 space-y-2">
                    <p class="text-sm font-black mb-2">🛵 Entrega</p>
                    <div class="flex justify-between text-sm">
                        <span class="text-zinc-500">Tipo</span>
                        <span class="font-semibold">{{ ucfirst(pedidoSelecionado.tipo_entrega) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-zinc-500">Pagamento</span>
                        <span class="font-semibold">{{ pedidoSelecionado.pagamento }}</span>
                    </div>
                    <div v-if="pedidoSelecionado.endereco" class="flex justify-between text-sm">
                        <span class="text-zinc-500">Endereço</span>
                        <span class="font-semibold text-right">
                            {{ pedidoSelecionado.endereco.rua }}, {{ pedidoSelecionado.endereco.numero }} — {{ pedidoSelecionado.endereco.bairro }}
                        </span>
                    </div>
                </div>

                <!-- ITENS -->
                <div class="bg-zinc-50 border border-zinc-200 rounded-2xl p-4">
                    <p class="text-sm font-black mb-3">🍔 Itens do pedido</p>
                    <div
                        v-for="item in pedidoSelecionado.itens"
                        :key="item.id"
                        class="flex justify-between text-sm py-2 border-b border-zinc-200 last:border-0"
                    >
                        <span>{{ item.quantidade }}x {{ item.nome }}</span>
                        <span class="font-semibold">R$ {{ item.subtotal }}</span>
                    </div>
                </div>

                <!-- TOTAL -->
                <div class="flex justify-between items-center bg-green-50 border border-green-200 rounded-2xl p-4">
                    <span class="font-black">Total</span>
                    <span class="text-xl font-black text-green-600">R$ {{ pedidoSelecionado.total }}</span>
                </div>

            </div>
        </div>
    </div>

</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'

const pedidos = ref([])
const filtroAtivo = ref('todos')
const pedidoSelecionado = ref(null)
const ultimoPedidoId = ref(null)
let intervalo = null
let audioCtx = null

const filtros = [
    { label: 'Todos', value: 'todos' },
    { label: 'Pendentes', value: 'pendente' },
    { label: 'Confirmados', value: 'confirmado' },
    { label: 'Em entrega', value: 'em_entrega' },
    { label: 'Prontos', value: 'pronto' },
    { label: 'Entregues', value: 'entregue' },
    { label: 'Cancelados', value: 'cancelado' },
]

const pedidosFiltrados = computed(() => {
    if (filtroAtivo.value === 'todos') return pedidos.value
    return pedidos.value.filter(p => p.status === filtroAtivo.value)
})

function inicializarAudio() {
    if (!audioCtx) {
        audioCtx = new (window.AudioContext || window.webkitAudioContext)()
    }
}

function tocarSom() {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)()
        ctx.resume().then(() => {
            function bip(inicio) {
                const oscillator = ctx.createOscillator()
                const gainNode = ctx.createGain()

                oscillator.connect(gainNode)
                gainNode.connect(ctx.destination)

                oscillator.frequency.value = 880
                gainNode.gain.value = 0.3

                oscillator.start(ctx.currentTime + inicio)
                oscillator.stop(ctx.currentTime + inicio + 0.2)
            }

            bip(0)
            bip(0.3)
            bip(0.6)
        })
    } catch (err) {
        console.error('Erro no áudio:', err)
    }
}

async function carregarPedidos(silencioso = false) {
    const res = await fetch('/api/admin/pedidos', {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('admin_token')}`,
            'Accept': 'application/json',
        },
    })
    const data = await res.json()
    const novosPedidos = data.pedidos ?? []

    if (!silencioso && ultimoPedidoId.value !== null) {
        const temNovo = novosPedidos.some(p => p.id > ultimoPedidoId.value && p.status === 'pendente')
        if (temNovo) tocarSom()
    }

    if (novosPedidos.length > 0) {
        ultimoPedidoId.value = Math.max(...novosPedidos.map(p => p.id))
    }

    pedidos.value = novosPedidos
}

function ucfirst(str) {
    if (!str) return ''
    return str.charAt(0).toUpperCase() + str.slice(1)
}

async function atualizarStatus(id, status) {
    const res = await fetch(`/api/admin/pedidos/${id}/status`, {
        method: 'PATCH',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('admin_token')}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ status }),
    })

    if (res.ok) {
        const pedido = pedidos.value.find(p => p.id === id)
        if (pedido) pedido.status = status
        if (pedidoSelecionado.value?.id === id) {
            pedidoSelecionado.value.status = status
        }
    }
}

onMounted(async () => {
    await carregarPedidos(true)
    intervalo = setInterval(() => carregarPedidos(), 15000)
})

onBeforeUnmount(() => {
    clearInterval(intervalo)
})

</script>
