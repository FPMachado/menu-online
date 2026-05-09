<template>
  <div class="min-h-screen bg-zinc-50 text-zinc-900">
    <!-- HEADER -->
    <header
      class="sticky top-0 z-50 border-b border-zinc-200/80 bg-white/90 backdrop-blur-xl"
    >
      <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 md:px-6">
        <div class="flex items-center gap-3">
          <div
            class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-red-500 to-orange-400 text-xl text-white shadow-lg"
          >
            🍔
          </div>

          <div>
            <h1 class="text-base font-bold md:text-lg">Burger House</h1>
            <div class="flex items-center gap-2 text-xs text-zinc-500">
              <span class="flex items-center gap-1 text-emerald-600 font-semibold">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                Aberto agora
              </span>

              <span>•</span>
              <span>Entrega em 25-35 min</span>
            </div>
          </div>
        </div>

        <button
          class="rounded-2xl border border-zinc-200 px-4 py-2 text-sm font-semibold transition hover:bg-zinc-100"
        >
          Entrar
        </button>
      </div>
    </header>

    <!-- HERO -->
    <section class="mx-auto max-w-7xl px-4 pt-5 md:px-6 md:pt-8">
      <div
        class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-zinc-950 via-red-950 to-orange-500 p-6 text-white shadow-2xl md:p-10"
      >
        <div
          class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/10 blur-2xl"
        ></div>
        <div
          class="absolute -bottom-10 left-10 h-40 w-40 rounded-full bg-orange-300/20 blur-2xl"
        ></div>

        <div class="relative z-10 grid gap-8 md:grid-cols-2 md:items-center">
          <div>
            <div
              class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold backdrop-blur"
            >
              🔥 Alta demanda hoje
            </div>

            <h2 class="max-w-xl text-3xl font-black leading-tight md:text-5xl">
              Burgers artesanais entregues quentinhos na sua porta.
            </h2>

            <p class="mt-4 max-w-lg text-sm text-white/85 md:text-base">
              Mais sabor, entrega rápida e combos irresistíveis. Faça seu pedido em menos
              de 1 minuto.
            </p>

            <div class="mt-5 flex flex-wrap gap-3 text-sm">
              <div class="rounded-2xl bg-white/10 px-4 py-2 backdrop-blur">
                ⭐ 4.8 (2.341 avaliações)
              </div>

              <div class="rounded-2xl bg-white/10 px-4 py-2 backdrop-blur">
                🚀 +12 mil pedidos entregues
              </div>
            </div>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
              <button
                class="rounded-2xl bg-white px-6 py-4 text-sm font-bold text-red-500 shadow-xl transition hover:scale-[1.02]"
              >
                Pedir Agora
              </button>

              <button
                class="rounded-2xl border border-white/20 px-6 py-4 text-sm font-semibold transition hover:bg-white/10"
              >
                Ver Promoções
              </button>
            </div>
          </div>

          <div class="hidden md:flex justify-end">
            <img
              src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=900"
              alt="Burger destaque"
              class="h-[340px] w-[340px] rounded-3xl object-cover shadow-2xl"
              loading="lazy"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- SEARCH -->
    <section class="mx-auto max-w-7xl px-4 pt-5 md:px-6">
      <div class="rounded-3xl border border-zinc-200 bg-white p-2 shadow-sm">
        <div class="flex items-center gap-3 px-3">
          <span class="text-lg text-zinc-400">🔎</span>

          <input
            type="text"
            placeholder="Buscar burger, combo, bebida..."
            class="h-12 w-full bg-transparent text-sm outline-none"
          />

          <button
            class="rounded-2xl bg-red-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-600"
          >
            Buscar
          </button>
        </div>
      </div>
    </section>

    <!-- FILTERS -->
    <section class="mx-auto max-w-7xl px-4 pt-5 md:px-6">
        <div class="flex gap-3 overflow-x-auto pb-2">
            <button
                v-for="categoria in categorias"
                :key="categoria.id"
                @click="rolarParaCategoria(categoria.id)"
                class="whitespace-nowrap rounded-2xl border px-4 py-2 text-sm font-semibold transition border-zinc-200 bg-white hover:bg-zinc-100"
            >
                {{ categoria.emoji }} {{ categoria.nome }}
            </button>
        </div>
    </section>

    <!-- PRODUCTS -->
    <section class="mx-auto max-w-7xl px-4 pb-32 pt-5 md:px-6">
        <div
            v-for="categoria in categorias"
            :key="categoria.id"
            :id="`categoria-${categoria.id}`"
            class="mb-10"
        >
            <!-- TÍTULO DA CATEGORIA -->
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-xl font-black md:text-2xl">
                        {{ categoria.emoji }} {{ categoria.nome }}
                    </h3>
                    <p class="text-sm text-zinc-500">
                        {{ categoria.produtos.length }} itens
                    </p>
                </div>
            </div>

            <!-- PRODUTOS DA CATEGORIA -->
            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                <article
                    v-for="item in categoria.produtos"
                    :key="item.id"
                    class="group overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl"
                >
                    <div class="relative">
                        <img
                            :src="item.imagem"
                            :alt="item.nome"
                            class="h-56 w-full object-cover transition duration-500 group-hover:scale-105"
                            loading="lazy"
                        />

                        <div
                            v-if="item.badge"
                            class="absolute left-3 top-3 rounded-full bg-white/90 px-3 py-1 text-xs font-bold text-zinc-800 shadow"
                        >
                            {{ item.badge }}
                        </div>
                    </div>

                    <div class="p-5">
                        <h4 class="text-lg font-bold">{{ item.nome }}</h4>
                        <p class="mt-1 text-sm text-zinc-500">{{ item.descricao }}</p>

                        <div class="mt-5 flex items-center justify-between">
                            <div>
                                <p v-if="item.preco_antigo" class="text-xs text-zinc-400 line-through">
                                    R$ {{ item.preco_antigo }}
                                </p>
                                <p class="text-2xl font-black text-red-500">R$ {{ item.preco }}</p>
                            </div>

                            <button
                                @click="adicionar(item)"
                                class="rounded-2xl bg-red-500 px-5 py-3 text-sm font-bold text-white shadow-lg transition hover:scale-[1.03] hover:bg-red-600 active:scale-95"
                            >
                                Adicionar
                            </button>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- TOAST -->
    <transition name="fade">
        <div
        v-if="toast"
        class="fixed left-1/2 top-5 z-[60] -translate-x-1/2 rounded-2xl bg-zinc-950 px-5 py-3 text-sm font-semibold text-white shadow-2xl"
        >
        {{ toast }}
        </div>
    </transition>

    <!-- CART -->
    <div class="fixed bottom-4 left-4 right-4 z-50 md:left-auto md:right-6 md:w-[360px]">
        <button
        class="flex w-full items-center justify-between rounded-3xl bg-emerald-500 px-5 py-4 text-white shadow-2xl transition hover:bg-emerald-600"
        @click="cart.state.aberto = true"
        >
        <span class="font-bold">🛒 Ver Carrinho</span>
        <span class="rounded-2xl bg-white/20 px-3 py-1 text-sm font-bold">
            {{ cart.totalItens.value }} itens
        </span>
        </button>
    </div>
    </div>
    <CartDrawer
    @checkout="
        cart.state.aberto = false;
        checkoutAberto = true;
    "
    />
    <CheckoutModal
    :aberto="checkoutAberto"
    :total="totalCheckout"
    @fechar="checkoutAberto = false"
    />
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import cart from "./stores/cart";
import CartDrawer from "./components/CartDrawer.vue";
import CheckoutModal from "./components/CheckoutModal.vue";

const carrinho = ref(2);
const toast = ref("");

const categorias = ref([])
const produtos = ref([])
const categoriaAtiva = ref(null)

function adicionar(item) {
    cart.adicionar(item);
}

const checkoutAberto = ref(false);

const totalCheckout = computed(() => {
    return (cart.subtotal.value + 6).toFixed(2);
});

onMounted(async () => {
    const res = await fetch('/api/cardapio')
    const data = await res.json()

    categorias.value = data.categorias ?? []
    produtos.value = categorias.value.flatMap(c => c.produtos)

    if (categorias.value.length > 0) {
        categoriaAtiva.value = categorias.value[0].id
    }
})

function rolarParaCategoria(id) {
    const el = document.getElementById(`categoria-${id}`)
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' })
}

</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translate(-50%, -10px);
}
</style>
