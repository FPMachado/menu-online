<template>
    <div class="p-8">

        <div class="mb-6">
            <h1 class="text-2xl font-black">Configurações</h1>
            <p class="text-sm text-zinc-500 mt-1">Dados do seu restaurante</p>
        </div>

        <!-- ABAS -->
        <div class="flex gap-2 mb-6 border-b border-zinc-200">
            <button
                v-for="aba in abas"
                :key="aba.id"
                @click="abaAtiva = aba.id"
                class="px-5 py-3 text-sm font-semibold transition border-b-2 -mb-[2px]"
                :class="abaAtiva === aba.id
                    ? 'border-red-500 text-red-500'
                    : 'border-transparent text-zinc-500 hover:text-zinc-700'"
            >
                {{ aba.emoji }} {{ aba.label }}
            </button>
        </div>

        <div class="bg-white rounded-2xl border border-zinc-200 p-6">

            <!-- ABA GERAL -->
            <div v-if="abaAtiva === 'geral'" class="grid md:grid-cols-2 gap-6">

                <div class="md:col-span-2">
                    <label class="text-sm font-semibold">Logo do restaurante</label>
                    <div class="mt-2 flex items-center gap-4">
                        <div
                            v-if="form.logo"
                            class="relative w-20 h-20 rounded-2xl overflow-hidden border border-zinc-200 shrink-0"
                        >
                            <img :src="form.logo" class="w-full h-full object-cover" />
                            <button
                                @click="form.logo = ''"
                                class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full text-xs font-bold"
                            >
                                ✕
                            </button>
                        </div>
                        <div
                            v-else
                            @click="$refs.inputLogo.click()"
                            class="w-20 h-20 border-2 border-dashed border-zinc-300 rounded-2xl flex flex-col items-center justify-center cursor-pointer hover:border-red-400 hover:bg-red-50 transition shrink-0"
                        >
                            <span class="text-2xl">🏪</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold">Logo</p>
                            <p class="text-xs text-zinc-500 mt-1">PNG, JPG ou WEBP até 5MB</p>
                            <button
                                @click="$refs.inputLogo.click()"
                                class="mt-2 text-xs bg-zinc-100 hover:bg-zinc-200 px-3 py-1.5 rounded-xl font-semibold transition"
                            >
                                Trocar logo
                            </button>
                        </div>
                        <input ref="inputLogo" type="file" accept="image/*" class="hidden" @change="uploadLogo" />
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold">Nome do restaurante</label>
                    <input
                        v-model="form.nome_restaurante"
                        type="text"
                        placeholder="Ex: Burger House"
                        class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
                    />
                </div>

                <div>
                    <label class="text-sm font-semibold">Slogan</label>
                    <input
                        v-model="form.slogan"
                        type="text"
                        placeholder="Ex: Os melhores burgers da cidade!"
                        class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
                    />
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-semibold">Descrição</label>
                    <textarea
                        v-model="form.descricao"
                        placeholder="Ex: Mais sabor, entrega rápida e combos irresistíveis."
                        rows="2"
                        class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition resize-none"
                    />
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-semibold">Imagem do hero</label>
                    <div class="mt-2 space-y-3">
                        <div
                            v-if="form.imagem_hero"
                            class="relative w-full h-40 rounded-2xl overflow-hidden border border-zinc-200"
                        >
                            <img :src="form.imagem_hero" class="w-full h-full object-cover" />
                            <button
                                @click="form.imagem_hero = ''"
                                class="absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full text-xs font-bold"
                            >
                                ✕
                            </button>
                        </div>
                        <div
                            v-else
                            @click="$refs.inputHero.click()"
                            class="w-full h-40 border-2 border-dashed border-zinc-300 rounded-2xl flex flex-col items-center justify-center cursor-pointer hover:border-red-400 hover:bg-red-50 transition"
                        >
                            <span class="text-3xl mb-2">🖼️</span>
                            <p class="text-sm font-semibold text-zinc-500">Clique para fazer upload</p>
                            <p class="text-xs text-zinc-400 mt-1">PNG, JPG ou WEBP até 5MB</p>
                        </div>
                        <input ref="inputHero" type="file" accept="image/*" class="hidden" @change="uploadHero" />
                    </div>
                </div>

            </div>

            <!-- ABA FUNCIONAMENTO -->
            <div v-if="abaAtiva === 'funcionamento'" class="grid md:grid-cols-2 gap-6">

                <div class="md:col-span-2">
                    <div class="flex items-center justify-between p-4 bg-zinc-50 rounded-2xl border border-zinc-200">
                        <div>
                            <p class="font-semibold text-sm">Restaurante aberto</p>
                            <p class="text-xs text-zinc-500 mt-0.5">Clientes podem fazer pedidos</p>
                        </div>
                        <button
                            @click="form.aberto = !form.aberto"
                            class="w-12 h-6 rounded-full transition"
                            :class="form.aberto ? 'bg-green-500' : 'bg-zinc-300'"
                        >
                            <div
                                class="w-5 h-5 bg-white rounded-full shadow transition-transform mx-0.5"
                                :class="form.aberto ? 'translate-x-6' : 'translate-x-0'"
                            ></div>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold">Horário de abertura</label>
                    <input
                        v-model="form.horario_abertura"
                        type="time"
                        class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
                    />
                </div>

                <div>
                    <label class="text-sm font-semibold">Horário de fechamento</label>
                    <input
                        v-model="form.horario_fechamento"
                        type="time"
                        class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
                    />
                </div>

                <div>
                    <label class="text-sm font-semibold">Tempo estimado de entrega</label>
                    <input
                        v-model="form.tempo_entrega"
                        type="text"
                        placeholder="Ex: 30-45 min"
                        class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
                    />
                </div>

                <div>
                    <label class="text-sm font-semibold">Taxa de entrega (R$)</label>
                    <input
                        v-model="form.taxa_entrega"
                        type="number"
                        step="0.01"
                        placeholder="Ex: 6.00"
                        class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
                    />
                </div>

            </div>

            <!-- ABA PAGAMENTO -->
            <div v-if="abaAtiva === 'pagamento'" class="grid md:grid-cols-2 gap-6">

                <div>
                    <label class="text-sm font-semibold">Chave PIX</label>
                    <input
                        v-model="form.chave_pix"
                        type="text"
                        placeholder="Ex: email@restaurante.com"
                        class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
                    />
                </div>

                <div>
                    <label class="text-sm font-semibold">Email para notificações</label>
                    <input
                        v-model="form.email_notificacao"
                        type="email"
                        placeholder="Ex: pedidos@restaurante.com"
                        class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
                    />
                </div>

            </div>

            <!-- BOTÃO SALVAR -->
            <div class="mt-6 pt-6 border-t border-zinc-200 flex items-center gap-4">
                <button
                    @click="salvar"
                    :disabled="salvando"
                    class="bg-red-500 hover:bg-red-600 text-white px-8 py-3 rounded-2xl font-bold transition disabled:opacity-60"
                >
                    {{ salvando ? 'Salvando...' : 'Salvar configurações' }}
                </button>

                <div v-if="sucesso" class="text-green-600 text-sm font-semibold">
                    ✅ Salvo com sucesso!
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const abaAtiva = ref('geral')
const salvando = ref(false)
const sucesso = ref(false)
const uploadando = ref(false)

const abas = [
    { id: 'geral', label: 'Geral', emoji: '🏪' },
    { id: 'funcionamento', label: 'Funcionamento', emoji: '🕐' },
    { id: 'pagamento', label: 'Pagamento', emoji: '💳' },
]

const form = ref({
    nome_restaurante: '',
    logo: '',
    slogan: '',
    descricao: '',
    imagem_hero: '',
    chave_pix: '',
    email_notificacao: '',
    taxa_entrega: '',
    aberto: true,
    horario_abertura: '08:00',
    horario_fechamento: '22:00',
    tempo_entrega: '30-45 min',
})

const token = localStorage.getItem('admin_token')
const headers = {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json',
    'Accept': 'application/json',
}

onMounted(async () => {
    const res = await fetch('/api/admin/configuracoes', { headers })
    const data = await res.json()
    if (data.configuracao) {
        form.value = data.configuracao
    }
})

async function upload(file) {
    const formData = new FormData()
    formData.append('imagem', file)

    const res = await fetch('/api/admin/upload', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json',
        },
        body: formData,
    })

    const data = await res.json()
    return data.url
}

async function uploadLogo(event) {
    const file = event.target.files[0]
    if (!file) return
    uploadando.value = true
    form.value.logo = await upload(file)
    uploadando.value = false
}

async function uploadHero(event) {
    const file = event.target.files[0]
    if (!file) return
    uploadando.value = true
    form.value.imagem_hero = await upload(file)
    uploadando.value = false
}

async function salvar() {
    salvando.value = true
    sucesso.value = false

    await fetch('/api/admin/configuracoes', {
        method: 'PUT',
        headers,
        body: JSON.stringify(form.value),
    })

    salvando.value = false
    sucesso.value = true
    setTimeout(() => sucesso.value = false, 3000)
}
</script>
