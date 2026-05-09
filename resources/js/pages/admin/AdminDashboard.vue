<template>
    <div class="p-8">

        <div class="mb-8">
            <h1 class="text-2xl font-black">Dashboard</h1>
            <p class="text-sm text-zinc-500 mt-1">Resumo do dia</p>
        </div>

        <!-- CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

            <div class="bg-white rounded-2xl border border-zinc-200 p-5">
                <p class="text-sm text-zinc-500">Pedidos hoje</p>
                <p class="text-3xl font-black mt-1">{{ stats.pedidosHoje }}</p>
                <p class="text-xs text-emerald-500 mt-1 font-semibold">🛵 Em andamento</p>
            </div>

            <div class="bg-white rounded-2xl border border-zinc-200 p-5">
                <p class="text-sm text-zinc-500">Faturamento hoje</p>
                <p class="text-3xl font-black mt-1">R$ {{ stats.faturamentoHoje }}</p>
                <p class="text-xs text-emerald-500 mt-1 font-semibold">💰 Total do dia</p>
            </div>

            <div class="bg-white rounded-2xl border border-zinc-200 p-5">
                <p class="text-sm text-zinc-500">Ticket médio</p>
                <p class="text-3xl font-black mt-1">R$ {{ stats.ticketMedio }}</p>
                <p class="text-xs text-zinc-400 mt-1">📊 Por pedido</p>
            </div>

            <div class="bg-white rounded-2xl border border-zinc-200 p-5">
                <p class="text-sm text-zinc-500">Clientes novos</p>
                <p class="text-3xl font-black mt-1">{{ stats.clientesNovos }}</p>
                <p class="text-xs text-zinc-400 mt-1">👤 Hoje</p>
            </div>

        </div>

        <!-- PEDIDOS RECENTES -->
        <div class="bg-white rounded-2xl border border-zinc-200">
            <div class="p-5 border-b border-zinc-200 flex justify-between items-center">
                <h2 class="font-black">Pedidos recentes</h2>
                <RouterLink to="/admin/pedidos" class="text-sm text-red-500 font-semibold hover:underline">
                    Ver todos
                </RouterLink>
            </div>

            <div class="divide-y divide-zinc-100">
                <div
                    v-for="pedido in pedidos"
                    :key="pedido.id"
                    class="p-5 flex items-center justify-between"
                >
                    <div>
                        <p class="font-bold text-sm">#{{ pedido.id }} — {{ pedido.cliente?.nome }}</p>
                        <p class="text-xs text-zinc-500 mt-0.5">{{ pedido.tipo_entrega }} • {{ pedido.pagamento }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-black text-green-600">R$ {{ pedido.total }}</p>
                        <span
                            class="text-xs font-semibold px-2 py-1 rounded-full"
                            :class="{
                                'bg-amber-100 text-amber-600': pedido.status === 'pendente',
                                'bg-green-100 text-green-600': pedido.status === 'confirmado',
                                'bg-blue-100 text-blue-600': pedido.status === 'entregue',
                            }"
                        >
                            {{ pedido.status }}
                        </span>
                    </div>
                </div>

                <div v-if="pedidos.length === 0" class="p-8 text-center text-zinc-400 text-sm">
                    Nenhum pedido ainda hoje
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'

const pedidos = ref([])
const stats = ref({
    pedidosHoje: 0,
    faturamentoHoje: '0,00',
    ticketMedio: '0,00',
    clientesNovos: 0,
})

onMounted(async () => {
    const res = await fetch('/api/admin/pedidos', {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('admin_token')}`,
            'Accept': 'application/json',
        },
    })

    const data = await res.json()
    pedidos.value = data.pedidos?.slice(0, 5) ?? []

    const total = pedidos.value.reduce((t, p) => t + parseFloat(p.total), 0)
    stats.value.pedidosHoje = pedidos.value.length
    stats.value.faturamentoHoje = total.toFixed(2).replace('.', ',')
    stats.value.ticketMedio = pedidos.value.length
        ? (total / pedidos.value.length).toFixed(2).replace('.', ',')
        : '0,00'
})
</script>
