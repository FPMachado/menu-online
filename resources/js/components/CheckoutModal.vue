<template>
  <div
    v-if="aberto"
    class="fixed inset-0 z-[60] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4"
  >
    <div class="bg-white w-full max-w-xl rounded-3xl shadow-2xl max-h-[95vh] overflow-y-auto">

      <!-- HEADER -->
      <div class="p-6 border-b flex justify-between items-center">
        <div>
          <h2 class="text-2xl font-black">Finalizar Pedido</h2>
          <p class="text-sm text-zinc-500">
            Etapa {{ step }} de 3
          </p>
        </div>

        <button
          @click="$emit('fechar')"
          class="w-10 h-10 rounded-full bg-zinc-100 hover:bg-zinc-200"
        >
          ✕
        </button>
      </div>

      <!-- STEP 1 -->
      <div v-if="step === 1" class="p-6 space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

          <!-- TELEFONE -->
          <div>
            <label class="text-sm font-semibold">WhatsApp</label>
            <input
              v-model="form.whatsapp"
              @input="form.whatsapp = maskTelefone(form.whatsapp)"
              type="text"
              class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
              placeholder="(00) 00000-0000"
            />
          </div>

          <!-- NOME -->
          <div>
            <label class="text-sm font-semibold">Seu nome</label>
            <input
              v-model="form.nome"
              type="text"
              :readonly="clienteExiste"
              :class="[
                clienteExiste ? 'bg-zinc-100 cursor-not-allowed' : '',
                erroNome ? 'border-red-500 shake' : ''
              ]"
              class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
              placeholder="Digite seu nome"
            />

            <p v-if="erroNome" class="text-sm text-red-500 mt-1">
              ⚠️ Nome é obrigatório para continuar
            </p>
          </div>

        </div>

        <button
          class="w-full bg-red-500 text-white py-3 rounded-2xl font-bold"
          @click="nextStep"
        >
          Continuar
        </button>
      </div>

      <!-- STEP 2 -->
      <div v-if="step === 2" class="p-6 space-y-5">

        <!-- ENDEREÇOS -->
        <div v-if="enderecos.length">
          <p class="text-sm font-bold mb-2">Escolha seu endereço:</p>

          <div
            v-for="end in enderecos"
            :key="end.id"
            @click="selecionarEndereco(end)"
            class="p-3 border rounded-2xl mb-2 text-sm cursor-pointer transition"
            :class="[
              enderecoSelecionado?.id === end.id
                ? 'border-green-500 bg-green-50'
                : 'hover:bg-zinc-50'
            ]"
          >
            🏠 {{ end.rua }}, {{ end.numero }} - {{ end.bairro }}
          </div>
        </div>

        <!-- FORM -->
        <div class="grid md:grid-cols-2 gap-4">

          <div>
            <label class="text-sm font-semibold">CEP</label>
            <input
              v-model="form.cep"
              @input="form.cep = maskCep(form.cep)"
              type="text"
              class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
              placeholder="00000-000"
            />
          </div>

          <div>
            <label class="text-sm font-semibold">Rua</label>
            <input
              v-model="form.rua"
              type="text"
              class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
            />
          </div>

          <div>
            <label class="text-sm font-semibold">Bairro</label>
            <input
              v-model="form.bairro"
              type="text"
              class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
            />
          </div>

          <div>
            <label class="text-sm font-semibold">Cidade</label>
            <input
              v-model="form.cidade"
              type="text"
              class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
            />
          </div>

          <div>
            <label class="text-sm font-semibold">Número</label>
            <input
              v-model="form.numero"
              type="text"
              class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
            />
          </div>

          <div>
            <label class="text-sm font-semibold">Complemento</label>
            <input
              v-model="form.complemento"
              type="text"
              placeholder="Ex: apto 101, bloco B..."
              class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
            />
          </div>

        </div>

            <div>
                <label class="text-sm font-semibold">Referência</label>
                <input
                v-model="form.referencia"
                type="text"
                placeholder="Ex: perto da padaria..."
                class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
                />
            </div>

        <div class="flex gap-2">
          <button class="w-full text-sm text-zinc-500" @click="prevStep">
            Voltar
          </button>

          <button
            class="w-full bg-red-500 text-white py-3 rounded-2xl font-bold"
            @click="nextStep"
          >
            Continuar
          </button>
        </div>
      </div>

      <!-- STEP 3 -->
      <div v-if="step === 3" class="p-6 space-y-5">
        <div class="grid gap-3">

          <div
            @click="form.pagamento = 'Pix'"
            class="p-4 border rounded-2xl cursor-pointer"
            :class="form.pagamento === 'Pix' ? 'border-green-500 bg-green-50' : ''"
          >
            🧾 Pix (instantâneo)
          </div>

          <div
            @click="form.pagamento = 'Cartão'"
            class="p-4 border rounded-2xl cursor-pointer"
            :class="form.pagamento === 'Cartão' ? 'border-green-500 bg-green-50' : ''"
          >
            💳 Cartão
          </div>

          <div
            @click="form.pagamento = 'Dinheiro'"
            class="p-4 border rounded-2xl cursor-pointer"
            :class="form.pagamento === 'Dinheiro' ? 'border-green-500 bg-green-50' : ''"
          >
            💵 Dinheiro
          </div>

        </div>

        <div class="bg-green-50 border border-green-200 p-4 rounded-2xl text-center mt-4">
          <p class="text-sm text-zinc-600">🧾 Total do pedido</p>
          <p class="text-3xl font-black text-green-600">
            R$ {{ total }}
          </p>
        </div>

        <div class="flex gap-2">
          <button class="w-full text-sm text-zinc-500" @click="prevStep">
            Voltar
          </button>

          <button class="w-full bg-green-500 hover:bg-green-600 text-white py-4 rounded-2xl font-bold text-lg">
            Confirmar Pedido
          </button>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { reactive, ref, watch } from 'vue'
import { maskTelefone, maskCep } from '@/utils/formatters'

const props = defineProps({
  aberto: Boolean,
  total: String
})

defineEmits(['fechar'])

const form = reactive({
    whatsapp: '',
    nome: '',
    cep: '',
    rua: '',
    bairro: '',
    cidade: '',
    numero: '',
    complemento: '',
    referencia: '',
    pagamento: 'Pix',
    obs: ''
})

const cliente = ref(null)
const clienteExiste = ref(null)
const enderecos = ref([])
const enderecoSelecionado = ref(null)

const step = ref(1)
const erroNome = ref(false)

const bloqueiaWatchCep = ref(false)

/* BUSCA CLIENTE */
watch(() => form.whatsapp, async (novo) => {
    const telefoneLimpo = novo ? novo.replace(/\D/g, '') : ''

    if (!telefoneLimpo || telefoneLimpo.length < 11) {
        clienteExiste.value = null
        cliente.value = null
        enderecos.value = []

        form.nome = ''
        limparEndereco()

        erroNome.value = false
        return
    }

    try {
        const res = await fetch(`/buscar-cliente?telefone=${telefoneLimpo}`)
        const data = await res.json()

        if (data.existe) {
            clienteExiste.value = true
            cliente.value = data.cliente

            form.nome = data.cliente.nome
            enderecos.value = data.cliente.enderecos ?? []
        } else {
            clienteExiste.value = false
            cliente.value = null
        }

    } catch (err) {
        console.error(err)
    }
})

/* CEP */
watch(() => form.cep, async (novo) => {

    if (bloqueiaWatchCep.value) return

    const cepLimpo = novo ? novo.replace(/\D/g, '') : ''

    if (cepLimpo.length !== 8) return

    try {
        const res = await fetch(`https://viacep.com.br/ws/${cepLimpo}/json/`)
        const data = await res.json()

        if (data.erro) return

        form.rua = data.logradouro || ''
        form.bairro = data.bairro || ''
        form.cidade = data.localidade || '' // 👈 AQUI É O PONTO CHAVE

        form.numero = ''

        enderecoSelecionado.value = null

    } catch (err) {
        console.error(err)
    }
})

/* SELEÇÃO ENDEREÇO (CORRIGIDO) */
function selecionarEndereco(end) {

    bloqueiaWatchCep.value = true

    enderecoSelecionado.value = end

    form.rua = end.rua || ''
    form.numero = end.numero || ''
    form.cep = end.cep || ''
    form.bairro = end.bairro || ''
    form.cidade = end.cidade || '' // 👈 continua indo pro backend

    form.complemento = end.complemento || ''
    form.referencia = end.referencia || ''

    requestAnimationFrame(() => {
        bloqueiaWatchCep.value = false
    })
}

/* LIMPAR ENDEREÇO (IMPORTANTE) */
function limparEndereco() {
    form.rua = ''
    form.numero = ''
    form.cep = ''
    form.bairro = ''
    form.cidade = ''
    //enderecoSelecionadoId.value = null
}

/* STEPS */
function nextStep() {
  if (step.value === 1) {
    if (!form.nome || form.nome.trim().length < 3) {
      erroNome.value = true
      return
    }
  }

  erroNome.value = false
  if (step.value < 3) step.value++
}

function prevStep() {
  if (step.value > 1) step.value--
}

</script>

<style scoped>
@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-4px); }
  50% { transform: translateX(4px); }
  75% { transform: translateX(-4px); }
}

.shake {
  animation: shake 0.25s ease;
}
</style>
