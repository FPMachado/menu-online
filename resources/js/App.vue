<template>
    <div class="min-h-screen bg-zinc-50 text-zinc-900">
        <!-- HEADER -->
        <header class="sticky top-0 z-50 border-b border-zinc-200/80 bg-white/90 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 md:px-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-red-500 to-orange-400 text-xl text-white shadow-lg overflow-hidden">
                        <img v-if="configuracao.logo" :src="configuracao.logo" class="w-full h-full object-cover" />
                        <span v-else>🍔</span>
                    </div>

                    <div>
                        <h1 class="text-base font-bold md:text-lg">{{ configuracao.nome_restaurante }}</h1>
                        <div class="flex items-center gap-2 text-xs text-zinc-500">
                            <span
                                class="flex items-center gap-1 font-semibold"
                                :class="configuracao.aberto ? 'text-emerald-600' : 'text-red-500'"
                            >
                                <span
                                    class="h-2 w-2 rounded-full"
                                    :class="configuracao.aberto ? 'bg-emerald-500' : 'bg-red-500'"
                                ></span>
                                {{ configuracao.aberto ? 'Aberto agora' : 'Fechado' }}
                            </span>
                            <span>•</span>
                            <span>Entrega em {{ configuracao.tempo_entrega }}</span>
                        </div>
                    </div>
                </div>

                <button class="rounded-2xl border border-zinc-200 px-4 py-2 text-sm font-semibold transition hover:bg-zinc-100">
                    Entrar
                </button>
            </div>
        </header>

        <!-- HERO -->
        <section class="mx-auto max-w-7xl px-4 pt-5 md:px-6 md:pt-8">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-zinc-950 via-red-950 to-orange-500 p-6 text-white shadow-2xl md:p-10">
                <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/10 blur-2xl"></div>
                <div class="absolute -bottom-10 left-10 h-40 w-40 rounded-full bg-orange-300/20 blur-2xl"></div>

                <div class="relative z-10 grid gap-8 md:grid-cols-2 md:items-center">
                    <div>
                        <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold backdrop-blur">
                            🔥 Alta demanda hoje
                        </div>

                        <h2 class="max-w-xl text-3xl font-black leading-tight md:text-5xl">
                            {{ configuracao.slogan || 'Peça agora e receba rapidinho!' }}
                        </h2>

                        <p class="mt-4 max-w-lg text-sm text-white/85 md:text-base">
                            {{ configuracao.descricao || 'Os melhores sabores entregues na sua porta.' }}
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
                            <button class="rounded-2xl bg-white px-6 py-4 text-sm font-bold text-red-500 shadow-xl transition hover:scale-[1.02]">
                                Pedir Agora
                            </button>
                            <button class="rounded-2xl border border-white/20 px-6 py-4 text-sm font-semibold transition hover:bg-white/10">
                                Ver Promoções
                            </button>
                        </div>
                    </div>

                    <div class="hidden md:flex justify-end">
                        <img
                            :src="configuracao.imagem_hero || 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=900'"
                            alt="Imagem destaque"
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
                        v-model="busca"
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
                    v-for="categoria in categoriasFiltradas"
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
                v-for="categoria in categoriasFiltradas"
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
                                :class="item.esgotado ? 'grayscale opacity-60' : ''"
                                loading="lazy"
                            />

                            <div
                                v-if="item.badge && !item.esgotado"
                                class="absolute left-3 top-3 rounded-full bg-white/90 px-3 py-1 text-xs font-bold text-zinc-800 shadow"
                            >
                                {{ item.badge }}
                            </div>

                            <div
                                v-if="item.esgotado"
                                class="absolute inset-0 flex items-center justify-center"
                            >
                                <span class="bg-zinc-900/70 text-white text-sm font-black px-4 py-2 rounded-full backdrop-blur-sm">
                                    😔 Esgotado
                                </span>
                            </div>
                        </div>

                        <div class="p-5">
                            <h4 class="text-lg font-bold" :class="item.esgotado ? 'text-zinc-400' : ''">
                                {{ item.nome }}
                            </h4>
                            <p class="mt-1 text-sm text-zinc-500">{{ item.descricao }}</p>

                            <div class="mt-5 flex items-center justify-between">
                                <div>
                                    <p v-if="item.preco_antigo" class="text-xs text-zinc-400 line-through">
                                        R$ {{ item.preco_antigo }}
                                    </p>
                                    <p class="text-2xl font-black" :class="item.esgotado ? 'text-zinc-400' : 'text-red-500'">
                                        R$ {{ item.preco }}
                                    </p>
                                </div>

                                <button
                                    @click="!item.esgotado && configuracao.aberto && adicionar(item)"
                                    class="rounded-2xl px-5 py-3 text-sm font-bold text-white shadow-lg transition-all duration-300"
                                    :class="item.esgotado || !configuracao.aberto
                                        ? 'bg-zinc-300 cursor-not-allowed'
                                        : produtoAdicionado === item.id
                                            ? 'bg-emerald-500 scale-95'
                                            : 'bg-red-500 hover:scale-[1.03] hover:bg-red-600 active:scale-95'"
                                    :disabled="item.esgotado || !configuracao.aberto"
                                >
                                    <span v-if="produtoAdicionado === item.id">✓ Adicionado!</span>
                                    <span v-else-if="item.esgotado">Esgotado</span>
                                    <span v-else-if="!configuracao.aberto">Fechado</span>
                                    <span v-else>Adicionar</span>
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

        <!-- VER PEDIDO ATIVO -->
        <div
            v-if="pedidoAtivoId"
            class="fixed bottom-20 left-4 right-4 z-50 md:left-auto md:right-6 md:w-[360px]"
        >
            <button
                @click="acompanhamentoAberto = true"
                class="flex w-full items-center justify-between rounded-3xl bg-red-500 px-5 py-3 text-white shadow-2xl transition hover:bg-red-600"
            >
                <span class="font-bold">📦 Ver meu pedido</span>
                <span class="rounded-2xl bg-white/20 px-3 py-1 text-sm font-bold">#{{ pedidoAtivoId }}</span>
            </button>
        </div>

        <!-- CART -->
        <div class="fixed bottom-4 left-4 right-4 z-50 md:left-auto md:right-6 md:w-[360px]">
            <button
                class="flex w-full items-center justify-between rounded-3xl px-5 py-4 text-white shadow-2xl transition hover:opacity-90"
                :class="configuracao.aberto ? 'bg-emerald-500 hover:bg-emerald-600' : 'bg-zinc-400 cursor-not-allowed'"
                @click="configuracao.aberto && (cart.state.aberto = true)"
            >
                <span class="font-bold">{{ configuracao.aberto ? '🛒 Ver Carrinho' : '🔒 Loja fechada' }}</span>
                <span class="rounded-2xl bg-white/20 px-3 py-1 text-sm font-bold">
                    {{ cart.totalItens.value }} itens
                </span>
            </button>
        </div>
    </div>

    <CartDrawer
        :taxaEntrega="parseFloat(configuracao.taxa_entrega || 0)"
        @checkout="cart.state.aberto = false; checkoutAberto = true"
    />

    <CheckoutModal
        :aberto="checkoutAberto"
        :total="totalCheckout"
        :configuracao="configuracao"
        @fechar="checkoutAberto = false"
        @pedidoCriado="(id) => { pedidoAtivoId = id; checkoutAberto = false }"
    />

    <AcompanhamentoPedido
        v-if="acompanhamentoAberto && pedidoAtivoId"
        :pedidoId="pedidoAtivoId"
        @fechar="acompanhamentoAberto = false"
        @pedidoFinalizado="pedidoAtivoId = null; localStorage.removeItem('pedido_ativo_id'); acompanhamentoAberto = false"
    />
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import cart from "./stores/cart";
import CartDrawer from "./components/CartDrawer.vue";
import CheckoutModal from "./components/CheckoutModal.vue";
import AcompanhamentoPedido from './components/AcompanhamentoPedido.vue'

const toast = ref("");
const categorias = ref([])
const busca = ref('')
const produtoAdicionado = ref(null)
const checkoutAberto = ref(false)

const configuracao = ref({
    nome_restaurante: 'Meu Restaurante',
    logo: null,
    tempo_entrega: '30-45 min',
    taxa_entrega: 6,
    aberto: true,
    chave_pix: '',
})

const totalCheckout = computed(() => {
    return (cart.subtotal.value + parseFloat(configuracao.value.taxa_entrega || 0)).toFixed(2)
})

const categoriasFiltradas = computed(() => {
    if (!busca.value.trim()) return categorias.value

    const termo = busca.value.toLowerCase()

    return categorias.value
        .map(categoria => ({
            ...categoria,
            produtos: categoria.produtos.filter(p =>
                p.nome.toLowerCase().includes(termo) ||
                p.descricao?.toLowerCase().includes(termo)
            )
        }))
        .filter(categoria => categoria.produtos.length > 0)
})

const pedidoAtivoId = ref(parseInt(localStorage.getItem('pedido_ativo_id')) || null)
const acompanhamentoAberto = ref(false)

function adicionar(item) {
    cart.adicionar(item)
    produtoAdicionado.value = item.id
    setTimeout(() => produtoAdicionado.value = null, 1500)
}

function rolarParaCategoria(id) {
    const el = document.getElementById(`categoria-${id}`)
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' })
}

function verificarHorario() {
    if (!configuracao.value.horario_abertura || !configuracao.value.horario_fechamento) return

    const agora = new Date()
    const horaAtual = agora.getHours() * 60 + agora.getMinutes()

    const [hAbertura, mAbertura] = configuracao.value.horario_abertura.split(':').map(Number)
    const [hFechamento, mFechamento] = configuracao.value.horario_fechamento.split(':').map(Number)

    const abertura = hAbertura * 60 + mAbertura
    const fechamento = hFechamento * 60 + mFechamento

    if (fechamento > abertura) {
        // Horário normal: ex 08:00 às 22:00
        configuracao.value.aberto = horaAtual >= abertura && horaAtual < fechamento
    } else {
        // Horário que passa da meia-noite: ex 18:00 às 02:00
        configuracao.value.aberto = horaAtual >= abertura || horaAtual < fechamento
    }
}

onMounted(async () => {
    const [resCardapio, resConfig] = await Promise.all([
        fetch('/api/cardapio'),
        fetch('/api/configuracoes'),
    ])

    const dataCardapio = await resCardapio.json()
    const dataConfig = await resConfig.json()

    categorias.value = dataCardapio.categorias ?? []

    if (dataConfig.configuracao) {
        configuracao.value = dataConfig.configuracao
    }

    if (dataConfig.configuracao) {
        configuracao.value = dataConfig.configuracao
        verificarHorario()
    }

    // Verificar se pedido ativo ainda está em andamento
    if (pedidoAtivoId.value) {
        const resPedido = await fetch(`/api/pedidos/${pedidoAtivoId.value}/status`)
        const dataPedido = await resPedido.json()

        if (dataPedido.pedido?.status === 'entregue' || dataPedido.pedido?.status === 'cancelado') {
            pedidoAtivoId.value = null
            localStorage.removeItem('pedido_ativo_id')
        }
    }
})


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
