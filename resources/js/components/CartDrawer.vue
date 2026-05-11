<template>
  <div
    v-if="cart.state.aberto"
    class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm"
    @click.self="cart.state.aberto = false"
  >
    <div
      class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col animate-slide"
    >
      <!-- HEADER -->
      <div class="border-b p-5 flex items-center justify-between">
        <div>
          <h2 class="text-xl font-black">Seu Carrinho</h2>
          <p class="text-sm text-zinc-500">
            {{ cart.totalItens.value }} itens adicionados
          </p>
        </div>

        <button
          @click="cart.state.aberto = false"
          class="w-10 h-10 rounded-xl bg-zinc-100 hover:bg-zinc-200"
        >
          ✕
        </button>
      </div>

      <!-- EMPTY -->
      <div
        v-if="cart.state.items.length === 0"
        class="flex-1 flex flex-col items-center justify-center text-center px-8"
      >
        <div class="text-6xl mb-4">🛒</div>

        <h3 class="font-bold text-lg">Seu carrinho está vazio</h3>

        <p class="text-zinc-500 text-sm mt-2">
          Adicione produtos deliciosos para continuar.
        </p>

        <button
          @click="cart.state.aberto = false"
          class="mt-5 bg-red-500 text-white px-5 py-3 rounded-2xl font-semibold"
        >
          Ver Cardápio
        </button>
      </div>

      <!-- ITEMS -->
      <div
        v-else
        class="flex-1 overflow-y-auto p-4 space-y-4"
      >
        <div
          v-for="item in cart.state.items"
          :key="item.id"
          class="border rounded-3xl p-3 flex gap-3"
        >
          <!-- IMG -->
          <img
            :src="item.imagem"
            class="w-24 h-24 rounded-2xl object-cover"
          />

          <!-- INFO -->
          <div class="flex-1">

            <div class="flex justify-between gap-2">
              <h3 class="font-bold leading-tight">
                {{ item.nome }}
              </h3>

                <button
                @click="cart.remover(item.id)"
                class="w-9 h-9 rounded-full text-red-400 hover:text-white hover:bg-red-500 transition flex items-center justify-center"
                >
                ✕
                </button>
            </div>

            <p class="text-sm text-zinc-500 mt-1">
              {{ item.descricao }}
            </p>

            <div class="mt-3 flex items-center justify-between">

              <span class="text-red-500 font-black text-lg">
                R$ {{ item.preco }}
              </span>

              <!-- QTD -->
              <div class="flex items-center gap-2">

                <button
                  @click="cart.diminuir(item.id)"
                  class="w-10 h-10 rounded-xl bg-zinc-100 hover:bg-zinc-200 active:scale-95"
                >
                  -
                </button>

                <span class="font-bold w-6 text-center">
                  {{ item.qtd }}
                </span>

                <button
                  @click="cart.aumentar(item.id)"
                  class="w-10 h-10 rounded-xl bg-zinc-100 hover:bg-zinc-200 active:scale-95"
                >
                  +
                </button>

              </div>

            </div>

          </div>
        </div>

        <!-- UPSELL -->
        <div class="pt-2">
          <h3 class="font-bold mb-3">Combine com isso 🔥</h3>

          <div class="grid grid-cols-2 gap-3 text-sm">

            <div class="border rounded-2xl p-3">
              🍟 Batata <br />
              <span class="font-bold text-red-500">+R$ 5</span>
            </div>

            <div class="border rounded-2xl p-3">
              🥤 Refri <br />
              <span class="font-bold text-red-500">+R$ 6</span>
            </div>

          </div>
        </div>

      </div>

        <!-- FOOTER -->
        <div v-if="cart.state.items.length > 0" class="border-t p-5 space-y-3 bg-white">
            <div class="flex justify-between text-sm text-zinc-500">
                <span>Subtotal</span>
                <span>R$ {{ cart.subtotal.value.toFixed(2) }}</span>
            </div>

            <div class="flex justify-between text-sm text-zinc-500">
                <span>Entrega</span>
                <span>{{ props.taxaEntrega > 0 ? `R$ ${props.taxaEntrega.toFixed(2)}` : 'Grátis' }}</span>
            </div>

            <div class="flex justify-between text-xl font-black">
                <span>Total</span>
                <span class="text-green-600">
                    R$ {{ (cart.subtotal.value + props.taxaEntrega).toFixed(2) }}
                </span>
            </div>

            <button
                @click="$emit('checkout')"
                class="w-full bg-green-500 hover:bg-green-600 text-white py-4 rounded-2xl font-bold text-lg shadow-xl"
            >
                Finalizar Pedido • R$ {{ (cart.subtotal.value + props.taxaEntrega).toFixed(2) }}
            </button>
        </div>

    </div>
  </div>
</template>

<script setup>
import cart from '../stores/cart'

const props = defineProps({
    taxaEntrega: {
        type: Number,
        default: 0
    }
})

defineEmits(['checkout'])
</script>

<style scoped>
@keyframes slide {
  from {
    transform: translateX(100%);
  }
  to {
    transform: translateX(0);
  }
}

.animate-slide {
  animation: slide .25s ease;
}
</style>
