<template>
    <div class="p-8 flex flex-col items-center text-center space-y-4">

        <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center text-4xl">
        ✅
        </div>

        <div>
            <h2 class="text-2xl font-black text-zinc-900">Pedido confirmado!</h2>
            <p class="text-zinc-500 text-sm mt-1">Seu pedido foi recebido com sucesso</p>
        </div>

        <div class="bg-zinc-50 border border-zinc-200 rounded-2xl p-4 w-full space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-zinc-500">Número do pedido</span>
                <span class="font-black">#{{ pedido.id }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-zinc-500">Pagamento</span>
                <span class="font-semibold">{{ pedido.pagamento }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-zinc-500">Total</span>
                <span class="font-black text-green-600">R$ {{ pedido.total }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-zinc-500">Status</span>
                <span class="font-semibold text-amber-500">⏳ Aguardando confirmação</span>
            </div>
        </div>

        <p class="text-xs text-zinc-400">
            Você receberá uma confirmação pelo WhatsApp em breve.
        </p>

        <button
            @click="$emit('fechar')"
            class="w-full bg-red-500 hover:bg-red-600 text-white py-4 rounded-2xl font-bold text-lg mt-2"
        >
            Voltar ao cardápio
        </button>
    </div>
</template>

<script setup>
defineProps({
  pedido: Object
})

defineEmits(['fechar'])
</script>
