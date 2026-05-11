<template>
    <div class="p-8">

        <div class="mb-8">
            <h1 class="text-2xl font-black">Configurações</h1>
            <p class="text-sm text-zinc-500 mt-1">Dados do seu restaurante</p>
        </div>

        <div class="bg-white rounded-2xl border border-zinc-200 p-6 space-y-6 max-w-2xl">

            <!-- LOGO -->
            <div>
                <label class="text-sm font-semibold">Logo do restaurante</label>

                <div class="mt-2 space-y-3">
                    <div
                        v-if="form.logo"
                        class="relative w-32 h-32 rounded-2xl overflow-hidden border border-zinc-200"
                    >
                        <img
                            :src="form.logo"
                            class="w-full h-full object-cover"
                        />
                        <button
                            @click="form.logo = ''"
                            class="absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full text-xs font-bold"
                        >
                            ✕
                        </button>
                    </div>

                    <div
                        v-else
                        @click="$refs.inputLogo.click()"
                        class="w-32 h-32 border-2 border-dashed border-zinc-300 rounded-2xl flex flex-col items-center justify-center cursor-pointer hover:border-red-400 hover:bg-red-50 transition"
                    >
                        <span class="text-3xl mb-1">🏪</span>
                        <p class="text-xs font-semibold text-zinc-500 text-center px-2">Upload logo</p>
                    </div>

                    <input
                        ref="inputLogo"
                        type="file"
                        accept="image/*"
                        class="hidden"
                        @change="uploadLogo"
                    />

                    <div v-if="uploadando" class="text-sm text-zinc-500">
                        ⏳ Enviando...
                    </div>
                </div>
            </div>

            <!-- NOME -->
            <div>
                <label class="text-sm font-semibold">Nome do restaurante</label>
                <input
                    v-model="form.nome_restaurante"
                    type="text"
                    placeholder="Ex: Burger House"
                    class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
                />
            </div>

            <!-- CHAVE PIX -->
            <div>
                <label class="text-sm font-semibold">Chave PIX</label>
                <input
                    v-model="form.chave_pix"
                    type="text"
                    placeholder="Ex: email@restaurante.com"
                    class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
                />
            </div>

            <!-- EMAIL -->
            <div>
                <label class="text-sm font-semibold">Email para notificações</label>
                <input
                    v-model="form.email_notificacao"
                    type="email"
                    placeholder="Ex: pedidos@restaurante.com"
                    class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
                />
            </div>

            <!-- TAXA DE ENTREGA -->
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

            <!-- HORÁRIOS -->
            <div class="grid grid-cols-2 gap-4">
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
            </div>

            <!-- TEMPO DE ENTREGA -->
            <div>
                <label class="text-sm font-semibold">Tempo estimado de entrega</label>
                <input
                    v-model="form.tempo_entrega"
                    type="text"
                    placeholder="Ex: 30-45 min"
                    class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
                />
            </div>

            <!-- STATUS -->
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

            <!-- BOTÃO SALVAR -->
            <button
                @click="salvar"
                :disabled="salvando"
                class="w-full bg-red-500 hover:bg-red-600 text-white py-4 rounded-2xl font-bold text-lg transition disabled:opacity-60"
            >
                {{ salvando ? 'Salvando...' : 'Salvar configurações' }}
            </button>

            <!-- SUCESSO -->
            <div v-if="sucesso" class="bg-green-50 border border-green-200 text-green-700 text-sm font-semibold p-4 rounded-2xl text-center">
                ✅ Configurações salvas com sucesso!
            </div>

        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const salvando = ref(false)
const sucesso = ref(false)

const form = ref({
    nome_restaurante: '',
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

const inputLogo = ref(null)
const uploadando = ref(false)

onMounted(async () => {
    const res = await fetch('/api/admin/configuracoes', { headers })
    const data = await res.json()
    if (data.configuracao) {
        form.value = data.configuracao
    }
})

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

async function uploadLogo(event) {
    const file = event.target.files[0]
    if (!file) return

    uploadando.value = true

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

    if (data.success) {
        form.value.logo = data.url
    }

    uploadando.value = false
}
</script>
