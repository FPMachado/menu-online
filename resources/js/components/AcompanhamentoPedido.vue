<template>
    <div
        v-if="pedido"
        class="fixed inset-0 z-[60] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4"
    >
        <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-6 space-y-5">

            <!-- HEADER -->
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-black">Meu Pedido</h2>
                    <p class="text-sm text-zinc-500">#{{ pedido.id }} • R$ {{ pedido.total }}</p>
                </div>
                <button
                    @click="$emit('fechar')"
                    class="w-10 h-10 rounded-full bg-zinc-100 hover:bg-zinc-200"
                >
                    ✕
                </button>
            </div>

            <!-- STATUS -->
            <div class="space-y-3">
                <div
                    v-for="etapa in etapas"
                    :key="etapa.status"
                    class="flex items-center gap-4"
                >
                    <div
                        class="w-10 h-10 rounded-full flex items-center justify-center text-lg shrink-0"
                        :class="etapaAtingida(etapa.status)
                            ? 'bg-green-100'
                            : 'bg-zinc-100'"
                    >
                        {{ etapa.emoji }}
                    </div>
                    <div class="flex-1">
                        <p
                            class="text-sm font-bold"
                            :class="etapaAtual(etapa.status)
                                ? 'text-green-600'
                                : etapaAtingida(etapa.status)
                                    ? 'text-zinc-700'
                                    : 'text-zinc-300'"
                        >
                            {{ etapa.label }}
                        </p>
                        <p
                            v-if="etapaAtual(etapa.status)"
                            class="text-xs text-zinc-400 mt-0.5"
                        >
                            {{ etapa.descricao }}
                        </p>
                    </div>
                    <div
                        v-if="etapaAtingida(etapa.status)"
                        class="text-green-500 font-black"
                    >
                        ✓
                    </div>
                </div>
            </div>

            <!-- ITENS -->
            <div class="bg-zinc-50 border border-zinc-200 rounded-2xl p-4">
                <p class="text-sm font-black mb-3">Itens do pedido</p>
                <div
                    v-for="item in pedido.itens"
                    :key="item.id"
                    class="flex justify-between text-sm py-1.5 border-b border-zinc-100 last:border-0"
                >
                    <span>{{ item.quantidade }}x {{ item.nome }}</span>
                    <span class="font-semibold">R$ {{ item.subtotal }}</span>
                </div>
            </div>

            <button
                @click="$emit('fechar')"
                class="w-full bg-red-500 hover:bg-red-600 text-white py-3 rounded-2xl font-bold transition"
            >
                Voltar ao cardápio
            </button>

        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'

const props = defineProps({
    pedidoId: Number,
})

defineEmits(['fechar', 'pedidoFinalizado'])

const pedido = ref(null)
let intervalo = null

const ordemStatus = ['pendente', 'confirmado', 'em_entrega', 'pronto', 'entregue']

const etapas = computed(() => {
    const tipoEntrega = pedido.value?.tipo_entrega

    return [
        { status: 'pendente', emoji: '⏳', label: 'Aguardando confirmação', descricao: 'O restaurante está analisando seu pedido' },
        { status: 'confirmado', emoji: '👨‍🍳', label: 'Preparando seu pedido', descricao: 'O restaurante confirmou e está preparando' },
        {
            status: tipoEntrega === 'entrega' ? 'em_entrega' : 'pronto',
            emoji: tipoEntrega === 'entrega' ? '🛵' : '🍽️',
            label: tipoEntrega === 'entrega' ? 'Saiu para entrega' : 'Pronto para retirada',
            descricao: tipoEntrega === 'entrega' ? 'Seu pedido está a caminho!' : 'Pode vir buscar!'
        },
        { status: 'entregue', emoji: '🏁', label: 'Entregue!', descricao: 'Bom apetite!' },
    ]
})

function etapaAtingida(status) {
    if (!pedido.value) return false
    const indexAtual = ordemStatus.indexOf(pedido.value.status)
    const indexEtapa = ordemStatus.indexOf(status)
    return indexEtapa <= indexAtual
}

function etapaAtual(status) {
    return pedido.value?.status === status
}

async function buscarStatus() {
    const res = await fetch(`/api/pedidos/${props.pedidoId}/status`)
    const data = await res.json()
    pedido.value = data.pedido

    if (data.pedido?.status === 'entregue' || data.pedido?.status === 'cancelado') {
        clearInterval(intervalo)
        emit('pedidoFinalizado')
    }
}

onMounted(async () => {
    await buscarStatus()
    intervalo = setInterval(buscarStatus, 15000)
})

onBeforeUnmount(() => {
    clearInterval(intervalo)
})
</script>
