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

        <!-- TIPO DE ENTREGA -->
        <div class="space-y-3">
            <p class="text-sm font-bold text-zinc-700">Como quer receber seu pedido?</p>

            <div
            @click="tipoEntrega = 'entrega'"
            class="flex items-center gap-4 p-4 border-2 rounded-2xl cursor-pointer transition"
            :class="tipoEntrega === 'entrega' ? 'border-red-500 bg-red-50' : 'border-zinc-200 hover:bg-zinc-50'"
            >
            <span class="text-2xl">🛵</span>
            <div>
                <p class="font-bold text-sm">Entrega</p>
                <p class="text-xs text-zinc-500">Receba em casa</p>
            </div>
            <div class="ml-auto w-5 h-5 rounded-full border-2 flex items-center justify-center"
                :class="tipoEntrega === 'entrega' ? 'border-red-500' : 'border-zinc-300'"
            >
                <div v-if="tipoEntrega === 'entrega'" class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
            </div>
            </div>

            <div
            @click="tipoEntrega = 'retirada'"
            class="flex items-center gap-4 p-4 border-2 rounded-2xl cursor-pointer transition"
            :class="tipoEntrega === 'retirada' ? 'border-red-500 bg-red-50' : 'border-zinc-200 hover:bg-zinc-50'"
            >
            <span class="text-2xl">🏪</span>
            <div>
                <p class="font-bold text-sm">Retirada</p>
                <p class="text-xs text-zinc-500 font-semibold text-emerald-600">Grátis</p>
            </div>
            <div class="ml-auto w-5 h-5 rounded-full border-2 flex items-center justify-center"
                :class="tipoEntrega === 'retirada' ? 'border-red-500' : 'border-zinc-300'"
            >
                <div v-if="tipoEntrega === 'retirada'" class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
            </div>
            </div>

            <div
            @click="tipoEntrega = 'local'"
            class="flex items-center gap-4 p-4 border-2 rounded-2xl cursor-pointer transition"
            :class="tipoEntrega === 'local' ? 'border-red-500 bg-red-50' : 'border-zinc-200 hover:bg-zinc-50'"
            >
            <span class="text-2xl">🍽️</span>
            <div>
                <p class="font-bold text-sm">Consumir no local</p>
                <p class="text-xs text-zinc-500">Comer aqui</p>
            </div>
            <div class="ml-auto w-5 h-5 rounded-full border-2 flex items-center justify-center"
                :class="tipoEntrega === 'local' ? 'border-red-500' : 'border-zinc-300'"
            >
                <div v-if="tipoEntrega === 'local'" class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
            </div>
            </div>
        </div>

        <!-- ENDEREÇO — só aparece se escolher entrega -->
        <div v-if="tipoEntrega === 'entrega'" class="space-y-4">

            <!-- ENDEREÇOS SALVOS -->
            <div v-if="enderecos.length">
            <p class="text-sm font-bold mb-2">Seus endereços:</p>
            <div
                v-for="end in enderecos"
                :key="end.id"
                @click="selecionarEndereco(end)"
                class="p-3 border-2 rounded-2xl mb-2 text-sm cursor-pointer transition"
                :class="enderecoSelecionado?.id === end.id ? 'border-red-500 bg-red-50' : 'border-zinc-200 hover:bg-zinc-50'"
            >
                <div class="flex items-center gap-2">
                <span>🏠</span>
                <span>{{ end.rua }}, {{ end.numero }} - {{ end.bairro }}</span>
                <div class="ml-auto w-5 h-5 rounded-full border-2 flex items-center justify-center"
                    :class="enderecoSelecionado?.id === end.id ? 'border-red-500' : 'border-zinc-300'"
                >
                    <div v-if="enderecoSelecionado?.id === end.id" class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
                </div>
                </div>
            </div>
            </div>

            <!-- FORMULÁRIO -->
            <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-semibold">CEP</label>
                <input v-model="form.cep" @input="form.cep = maskCep(form.cep)" type="text"
                class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
                placeholder="00000-000" />
            </div>
            <div>
                <label class="text-sm font-semibold">Rua</label>
                <input v-model="form.rua" type="text"
                class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition" />
            </div>
            <div>
                <label class="text-sm font-semibold">Bairro</label>
                <input v-model="form.bairro" type="text"
                class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition" />
            </div>
            <div>
                <label class="text-sm font-semibold">Cidade</label>
                <input v-model="form.cidade" type="text"
                class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition" />
            </div>
            <div>
                <label class="text-sm font-semibold">Número</label>
                <input v-model="form.numero" type="text"
                class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition" />
            </div>
            <div>
                <label class="text-sm font-semibold">Complemento</label>
                <input v-model="form.complemento" type="text" placeholder="Ex: apto 101, bloco B..."
                class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition" />
            </div>
            </div>

            <div>
            <label class="text-sm font-semibold">Referência</label>
            <input v-model="form.referencia" type="text" placeholder="Ex: perto da padaria..."
                class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition" />
            </div>
        </div>

        <div class="flex gap-2 pt-2">
            <button class="w-full text-sm text-zinc-500" @click="prevStep">Voltar</button>
            <button
            class="w-full bg-red-500 text-white py-3 rounded-2xl font-bold disabled:opacity-40 disabled:cursor-not-allowed"
            :disabled="!tipoEntrega"
            @click="nextStep"
            >
            Continuar
            </button>
        </div>

        </div>


      <!-- STEP 3 -->
        <div v-if="step === 3" class="p-6 space-y-4">

        <!-- MÉTODOS -->
        <div class="grid gap-3">

            <div
            @click="form.pagamento = 'Pix'"
            class="flex items-center gap-4 p-4 border-2 rounded-2xl cursor-pointer transition"
            :class="form.pagamento === 'Pix' ? 'border-green-500 bg-green-50' : 'border-zinc-200 hover:bg-zinc-50'"
            >
            <span class="text-2xl">🧾</span>
            <div class="flex-1">
                <p class="font-bold text-sm">Pix</p>
                <p class="text-xs text-zinc-500">Pagamento instantâneo</p>
            </div>
            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                :class="form.pagamento === 'Pix' ? 'border-green-500' : 'border-zinc-300'"
            >
                <div v-if="form.pagamento === 'Pix'" class="w-2.5 h-2.5 rounded-full bg-green-500"></div>
            </div>
            </div>

            <div
            @click="form.pagamento = 'Cartão'"
            class="flex items-center gap-4 p-4 border-2 rounded-2xl cursor-pointer transition"
            :class="form.pagamento === 'Cartão' ? 'border-green-500 bg-green-50' : 'border-zinc-200 hover:bg-zinc-50'"
            >
            <span class="text-2xl">💳</span>
            <div class="flex-1">
                <p class="font-bold text-sm">Cartão</p>
                <p class="text-xs text-zinc-500">Crédito ou débito</p>
            </div>
            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                :class="form.pagamento === 'Cartão' ? 'border-green-500' : 'border-zinc-300'"
            >
                <div v-if="form.pagamento === 'Cartão'" class="w-2.5 h-2.5 rounded-full bg-green-500"></div>
            </div>
            </div>

            <div
            @click="form.pagamento = 'Dinheiro'"
            class="flex items-center gap-4 p-4 border-2 rounded-2xl cursor-pointer transition"
            :class="form.pagamento === 'Dinheiro' ? 'border-green-500 bg-green-50' : 'border-zinc-200 hover:bg-zinc-50'"
            >
            <span class="text-2xl">💵</span>
            <div class="flex-1">
                <p class="font-bold text-sm">Dinheiro</p>
                <p class="text-xs text-zinc-500">Pague na entrega</p>
            </div>
            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                :class="form.pagamento === 'Dinheiro' ? 'border-green-500' : 'border-zinc-300'"
            >
                <div v-if="form.pagamento === 'Dinheiro'" class="w-2.5 h-2.5 rounded-full bg-green-500"></div>
            </div>
            </div>

        </div>

        <!-- PIX -->
        <div v-if="form.pagamento === 'Pix'" class="border-2 border-green-200 bg-green-50 rounded-2xl p-4 space-y-4">
            <p class="text-sm font-bold text-center text-green-700">Escaneie o QR Code para pagar</p>

            <div class="flex justify-center">
            <img
                src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=00020126580014br.gov.bcb.pix0136exemplo-chave-pix-fake5204000053039865802BR5913Burger+House6009Sao+Paulo62070503***6304FAKE"
                alt="QR Code PIX"
                class="w-44 h-44 rounded-2xl"
            />
            </div>

            <div class="bg-white border border-green-200 rounded-2xl p-3">
            <p class="text-xs text-zinc-500 mb-1">Chave PIX</p>
            <div class="flex items-center justify-between gap-2">
                <p class="text-sm font-mono font-bold truncate">exemplo@burguerhouse.com</p>
                <button
                @click="copiarPix"
                class="text-xs bg-green-500 text-white px-3 py-1.5 rounded-xl font-semibold shrink-0"
                >
                {{ pixCopiado ? '✓ Copiado' : 'Copiar' }}
                </button>
            </div>
            </div>

            <p class="text-xs text-center text-zinc-400">Após o pagamento, seu pedido será confirmado automaticamente</p>
        </div>

        <!-- DINHEIRO -->
        <div v-if="form.pagamento === 'Dinheiro'" class="border-2 border-zinc-200 bg-zinc-50 rounded-2xl p-4 space-y-3">
            <p class="text-sm font-bold text-zinc-700">Precisa de troco?</p>

            <div>
                <label class="text-sm text-zinc-500">Troco para quanto?</label>
                <input
                v-model="trocoPara"
                type="number"
                placeholder="Ex: 50"
                class="mt-2 w-full bg-white border border-zinc-200 rounded-2xl px-4 py-3 focus:border-zinc-400 focus:ring-2 focus:ring-zinc-100 outline-none transition"
                />
            </div>

            <div v-if="troco" class="bg-white border border-zinc-200 rounded-2xl p-3 flex justify-between items-center">
                <span class="text-sm text-zinc-500">Seu troco será</span>
                <span class="text-lg font-black text-green-600">R$ {{ troco }}</span>
            </div>

            <div v-if="trocoPara && !troco && parseFloat(trocoPara) > 0" class="text-xs text-red-500">
                ⚠️ O valor informado é menor que o total do pedido
            </div>
        </div>

        <!-- CARTÃO -->
        <div v-if="form.pagamento === 'Cartão'" class="border-2 border-zinc-200 bg-zinc-50 rounded-2xl p-4 space-y-3">
        <p class="text-sm font-bold text-zinc-700">Tipo do cartão</p>

        <div class="grid grid-cols-2 gap-3">
            <div
                @click="tipoCartao = 'credito'"
                class="flex items-center justify-center gap-2 p-4 border-2 rounded-2xl cursor-pointer transition"
                :class="tipoCartao === 'credito' ? 'border-green-500 bg-green-50' : 'border-zinc-200 bg-white hover:bg-zinc-100'"
                >
                <span class="text-xl">💳</span>
                <span class="font-bold text-sm">Crédito</span>
                </div>

                <div
                @click="tipoCartao = 'debito'"
                class="flex items-center justify-center gap-2 p-4 border-2 rounded-2xl cursor-pointer transition"
                :class="tipoCartao === 'debito' ? 'border-green-500 bg-green-50' : 'border-zinc-200 bg-white hover:bg-zinc-100'"
                >
                <span class="text-xl">💳</span>
                <span class="font-bold text-sm">Débito</span>
                </div>
            </div>

            <p class="text-xs text-center text-zinc-400">A maquininha será apresentada na entrega</p>
        </div>

        <!-- TOTAL -->
        <div class="bg-zinc-50 border border-zinc-200 p-4 rounded-2xl">
            <div class="flex justify-between text-sm text-zinc-500 mb-1">
            <span>Subtotal</span>
            <span>R$ {{ (parseFloat(total) - 6).toFixed(2) }}</span>
            </div>
            <div class="flex justify-between text-sm text-zinc-500 mb-2">
            <span>Entrega</span>
            <span>R$ 6,00</span>
            </div>
            <div class="flex justify-between font-black text-lg">
            <span>Total</span>
            <span class="text-green-600">R$ {{ total }}</span>
            </div>
        </div>

        <div class="flex gap-2 pt-2">
            <button class="w-full text-sm text-zinc-500" @click="prevStep">Voltar</button>
            <button
            v-if="form.pagamento === 'Dinheiro' || (form.pagamento === 'Cartão' && tipoCartao)"
            class="w-full bg-green-500 hover:bg-green-600 text-white py-4 rounded-2xl font-bold text-lg"
            >
            Confirmar Pedido
            </button>
        </div>

        </div>

    </div>
  </div>
</template>

<script setup>
import { reactive, ref, watch, computed } from 'vue'
import { maskTelefone, maskCep } from '@/utils/formatters'

const props = defineProps({
  aberto: Boolean,
  total: String
})

const pixCopiado = ref(false)
const trocoPara = ref('')

const troco = computed(() => {
  const valor = parseFloat(trocoPara.value)
  const totalNum = parseFloat(props.total)
  if (!valor || valor <= totalNum) return null
  return (valor - totalNum).toFixed(2)
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
const tipoEntrega = ref(null) // 'entrega', 'retirada', 'local'
const tipoCartao = ref(null)

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
        const res = await fetch(`/api/buscar-cliente?telefone=${telefoneLimpo}`)
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

    if (step.value === 2 && tipoEntrega.value !== 'entrega') {
        step.value = 3
        return
    }

    erroNome.value = false
    if (step.value < 3) step.value++
}

function prevStep() {
    if (step.value === 3 && tipoEntrega.value !== 'entrega') {
        step.value = 2
        return
    }
    if (step.value > 1) step.value--
}

function copiarPix() {
  const texto = 'exemplo@burguerhouse.com'

  if (navigator.clipboard) {
    navigator.clipboard.writeText(texto)
  } else {
    const el = document.createElement('textarea')
    el.value = texto
    document.body.appendChild(el)
    el.select()
    document.execCommand('copy')
    document.body.removeChild(el)
  }

  pixCopiado.value = true
  setTimeout(() => pixCopiado.value = false, 3000)
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
