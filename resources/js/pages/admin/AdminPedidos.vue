<template>
    <div class="p-8">

        <div class="mb-8">
            <h1 class="text-2xl font-black">Pedidos</h1>
            <p class="text-sm text-zinc-500 mt-1">Gerencie todos os pedidos</p>
        </div>

        <!-- FILTROS -->
        <div class="flex gap-3 mb-6">
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
                    class="p-5 flex items-center justify-between gap-4"
                >
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <p class="font-black">#{{ pedido.id }}</p>
                            <span
                                class="text-xs font-semibold px-2 py-1 rounded-full"
                                :class="{
                                    'bg-amber-100 text-amber-600': pedido.status === 'pendente',
                                    'bg-green-100 text-green-600': pedido.status === 'confirmado',
                                    'bg-blue-100 text-blue-600': pedido.status === 'entregue',
                                    'bg-red-100 text-red-600': pedido.status === 'cancelado',
                                }"
                            >
                                {{ pedido.status }}
                            </span>
                        </div>
                        <p class="text-sm text-zinc-600 mt-1">{{ pedido.cliente?.nome }} • {{ pedido.cliente?.telefone }}</p>
                        <p class="text-xs text-zinc-400 mt-0.5">{{ pedido.tipo_entrega }} • {{ pedido.pagamento }}</p>
                    </div>

                    <div class="text-right">
                        <p class="font-black text-green-600 text-lg">R$ {{ pedido.total }}</p>
                        <select
                            :value="pedido.status"
                            @change="atualizarStatus(pedido.id, $event.target.value)"
                            class="mt-2 text-xs border border-zinc-200 rounded-xl px-2 py-1 outline-none"
                        >
                            <option value="pendente">Pendente</option>
                            <option value="confirmado">Confirmado</option>
                            <option value="entregue">Entregue</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>
                </div>

                <div v-if="pedidosFiltrados.length === 0" class="p-8 text-center text-zinc-400 text-sm">
                    Nenhum pedido encontrado
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const pedidos = ref([])
const filtroAtivo = ref('todos')

const filtros = [
    { label: 'Todos', value: 'todos' },
    { label: 'Pendentes', value: 'pendente' },
    { label: 'Confirmados', value: 'confirmado' },
    { label: 'Entregues', value: 'entregue' },
    { label: 'Cancelados', value: 'cancelado' },
]

const pedidosFiltrados = computed(() => {
    if (filtroAtivo.value === 'todos') return pedidos.value
    return pedidos.value.filter(p => p.status === filtroAtivo.value)
})

onMounted(async () => {
    const res = await fetch('/api/admin/pedidos', {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('admin_token')}`,
            'Accept': 'application/json',
        },
    })
    const data = await res.json()
    pedidos.value = data.pedidos ?? []
})

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
    }
}
</script>
