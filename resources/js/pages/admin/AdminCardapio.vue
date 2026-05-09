<template>
    <div class="p-8">

        <!-- HEADER -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black">Cardápio</h1>
                <p class="text-sm text-zinc-500 mt-1">Gerencie categorias e produtos</p>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-6">

            <!-- CATEGORIAS -->
            <div class="col-span-1">
                <div class="bg-white rounded-2xl border border-zinc-200">
                    <div class="p-4 border-b border-zinc-200 flex justify-between items-center">
                        <h2 class="font-black text-sm">Categorias</h2>
                        <button
                            @click="abrirModalCategoria()"
                            class="text-xs bg-red-500 text-white px-3 py-1.5 rounded-xl font-semibold"
                        >
                            + Nova
                        </button>
                    </div>

                    <div class="divide-y divide-zinc-100">
                        <div
                            v-for="categoria in categorias"
                            :key="categoria.id"
                            @click="categoriaSelecionada = categoria"
                            class="p-4 flex items-center justify-between cursor-pointer transition"
                            :class="categoriaSelecionada?.id === categoria.id ? 'bg-red-50' : 'hover:bg-zinc-50'"
                        >
                            <div class="flex items-center gap-2">
                                <span>{{ categoria.emoji }}</span>
                                <span class="text-sm font-semibold">{{ categoria.nome }}</span>
                            </div>
                            <div class="flex gap-2">
                                <button
                                    @click.stop="abrirModalCategoria(categoria)"
                                    class="text-xs text-zinc-400 hover:text-zinc-700"
                                >
                                    ✏️
                                </button>
                                <button
                                    @click.stop="deletarCategoria(categoria.id)"
                                    class="text-xs text-zinc-400 hover:text-red-500"
                                >
                                    🗑️
                                </button>
                            </div>
                        </div>

                        <div v-if="categorias.length === 0" class="p-6 text-center text-zinc-400 text-sm">
                            Nenhuma categoria
                        </div>
                    </div>
                </div>
            </div>

            <!-- PRODUTOS -->
            <div class="col-span-2">
                <div class="bg-white rounded-2xl border border-zinc-200">
                    <div class="p-4 border-b border-zinc-200 flex justify-between items-center">
                        <h2 class="font-black text-sm">
                            {{ categoriaSelecionada ? `Produtos — ${categoriaSelecionada.emoji} ${categoriaSelecionada.nome}` : 'Selecione uma categoria' }}
                        </h2>
                        <button
                            v-if="categoriaSelecionada"
                            @click="abrirModalProduto()"
                            class="text-xs bg-red-500 text-white px-3 py-1.5 rounded-xl font-semibold"
                        >
                            + Novo Produto
                        </button>
                    </div>

                    <div class="divide-y divide-zinc-100">
                        <div
                            v-for="produto in produtosFiltrados"
                            :key="produto.id"
                            class="p-4 flex items-center gap-4"
                        >
                            <img
                                :src="produto.imagem || 'https://via.placeholder.com/60'"
                                class="w-14 h-14 rounded-xl object-cover"
                            />
                            <div class="flex-1">
                                <p class="font-bold text-sm">{{ produto.nome }}</p>
                                <p class="text-xs text-zinc-500 mt-0.5">{{ produto.descricao }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-sm font-black text-red-500">R$ {{ produto.preco }}</span>
                                    <span v-if="produto.preco_antigo" class="text-xs text-zinc-400 line-through">R$ {{ produto.preco_antigo }}</span>
                                    <span v-if="produto.badge" class="text-xs bg-amber-100 text-amber-600 px-2 py-0.5 rounded-full font-semibold">{{ produto.badge }}</span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button
                                    @click="abrirModalProduto(produto)"
                                    class="text-xs text-zinc-400 hover:text-zinc-700"
                                >
                                    ✏️
                                </button>
                                <button
                                    @click="deletarProduto(produto.id)"
                                    class="text-xs text-zinc-400 hover:text-red-500"
                                >
                                    🗑️
                                </button>
                            </div>
                        </div>

                        <div v-if="!categoriaSelecionada" class="p-8 text-center text-zinc-400 text-sm">
                            👈 Selecione uma categoria para ver os produtos
                        </div>

                        <div v-else-if="produtosFiltrados.length === 0" class="p-8 text-center text-zinc-400 text-sm">
                            Nenhum produto nessa categoria
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL CATEGORIA -->
        <div
            v-if="modalCategoria"
            class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4"
        >
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 space-y-4">
                <h2 class="text-xl font-black">{{ formCategoria.id ? 'Editar' : 'Nova' }} Categoria</h2>

                <div>
                    <label class="text-sm font-semibold">Emoji</label>
                    <input
                        v-model="formCategoria.emoji"
                        type="text"
                        placeholder="🍔"
                        class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 outline-none focus:border-red-400 transition"
                    />
                </div>

                <div>
                    <label class="text-sm font-semibold">Nome</label>
                    <input
                        v-model="formCategoria.nome"
                        type="text"
                        placeholder="Ex: Burgers"
                        class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 outline-none focus:border-red-400 transition"
                    />
                </div>

                <div class="flex gap-2 pt-2">
                    <button
                        @click="modalCategoria = false"
                        class="w-full text-sm text-zinc-500 py-3 rounded-2xl border border-zinc-200"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="salvarCategoria"
                        class="w-full bg-red-500 text-white py-3 rounded-2xl font-bold text-sm"
                    >
                        Salvar
                    </button>
                </div>
            </div>
        </div>

        <!-- MODAL PRODUTO -->
        <div
            v-if="modalProduto"
            class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4"
        >
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 space-y-4 max-h-[90vh] overflow-y-auto">
                <h2 class="text-xl font-black">{{ formProduto.id ? 'Editar' : 'Novo' }} Produto</h2>

                <div>
                    <label class="text-sm font-semibold">Nome</label>
                    <input
                        v-model="formProduto.nome"
                        type="text"
                        placeholder="Ex: Double Smash Bacon"
                        class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 outline-none focus:border-red-400 transition"
                    />
                </div>

                <div>
                    <label class="text-sm font-semibold">Descrição</label>
                    <textarea
                        v-model="formProduto.descricao"
                        placeholder="Ex: 2 carnes smash, cheddar duplo..."
                        rows="2"
                        class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 outline-none focus:border-red-400 transition resize-none"
                    />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold">Preço</label>
                        <input
                            v-model="formProduto.preco"
                            type="number"
                            step="0.01"
                            placeholder="29.90"
                            class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 outline-none focus:border-red-400 transition"
                        />
                    </div>
                    <div>
                        <label class="text-sm font-semibold">Preço antigo</label>
                        <input
                            v-model="formProduto.preco_antigo"
                            type="number"
                            step="0.01"
                            placeholder="34.90"
                            class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 outline-none focus:border-red-400 transition"
                        />
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold">Badge</label>
                    <input
                        v-model="formProduto.badge"
                        type="text"
                        placeholder="Ex: Mais pedido, Promoção..."
                        class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 outline-none focus:border-red-400 transition"
                    />
                </div>

                <div>
                    <label class="text-sm font-semibold">URL da imagem</label>
                    <input
                        v-model="formProduto.imagem"
                        type="text"
                        placeholder="https://..."
                        class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 outline-none focus:border-red-400 transition"
                    />
                </div>

                <div class="flex gap-2 pt-2">
                    <button
                        @click="modalProduto = false"
                        class="w-full text-sm text-zinc-500 py-3 rounded-2xl border border-zinc-200"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="salvarProduto"
                        class="w-full bg-red-500 text-white py-3 rounded-2xl font-bold text-sm"
                    >
                        Salvar
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const categorias = ref([])
const produtos = ref([])
const categoriaSelecionada = ref(null)
const modalCategoria = ref(false)
const modalProduto = ref(false)

const formCategoria = ref({ id: null, nome: '', emoji: '' })
const formProduto = ref({ id: null, nome: '', descricao: '', preco: '', preco_antigo: '', badge: '', imagem: '' })

const produtosFiltrados = computed(() => {
    if (!categoriaSelecionada.value) return []
    return produtos.value.filter(p => p.categoria_id === categoriaSelecionada.value.id)
})

const token = localStorage.getItem('admin_token')
const headers = {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json',
    'Accept': 'application/json',
}

onMounted(async () => {
    await carregarCategorias()
    await carregarProdutos()
})

async function carregarCategorias() {
    const res = await fetch('/api/admin/categorias', { headers })
    const data = await res.json()
    categorias.value = data.categorias ?? []
}

async function carregarProdutos() {
    const res = await fetch('/api/admin/produtos', { headers })
    const data = await res.json()
    produtos.value = data.produtos ?? []
}

function abrirModalCategoria(categoria = null) {
    formCategoria.value = categoria
        ? { id: categoria.id, nome: categoria.nome, emoji: categoria.emoji }
        : { id: null, nome: '', emoji: '' }
    modalCategoria.value = true
}

function abrirModalProduto(produto = null) {
    formProduto.value = produto
        ? { ...produto }
        : { id: null, nome: '', descricao: '', preco: '', preco_antigo: '', badge: '', imagem: '', categoria_id: categoriaSelecionada.value.id }
    modalProduto.value = true
}

async function salvarCategoria() {
    const url = formCategoria.value.id
        ? `/api/admin/categorias/${formCategoria.value.id}`
        : '/api/admin/categorias'

    const method = formCategoria.value.id ? 'PUT' : 'POST'

    await fetch(url, {
        method,
        headers,
        body: JSON.stringify(formCategoria.value),
    })

    modalCategoria.value = false
    await carregarCategorias()
}

async function deletarCategoria(id) {
    if (!confirm('Deletar categoria e todos os produtos dela?')) return

    await fetch(`/api/admin/categorias/${id}`, { method: 'DELETE', headers })
    categoriaSelecionada.value = null
    await carregarCategorias()
    await carregarProdutos()
}

async function salvarProduto() {
    const url = formProduto.value.id
        ? `/api/admin/produtos/${formProduto.value.id}`
        : '/api/admin/produtos'

    const method = formProduto.value.id ? 'PUT' : 'POST'

    await fetch(url, {
        method,
        headers,
        body: JSON.stringify({
            ...formProduto.value,
            categoria_id: categoriaSelecionada.value.id,
        }),
    })

    modalProduto.value = false
    await carregarProdutos()
}

async function deletarProduto(id) {
    if (!confirm('Deletar produto?')) return

    await fetch(`/api/admin/produtos/${id}`, { method: 'DELETE', headers })
    await carregarProdutos()
}
</script>
