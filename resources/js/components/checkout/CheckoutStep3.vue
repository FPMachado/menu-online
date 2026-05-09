<template>
  <div class="p-6 space-y-4">
    <!-- MÉTODOS -->
    <div class="grid gap-3">
      <div
        @click="$emit('update:form', { ...form, pagamento: 'Pix' })"
        class="flex items-center gap-4 p-4 border-2 rounded-2xl cursor-pointer transition"
        :class="
          form.pagamento === 'Pix'
            ? 'border-green-500 bg-green-50'
            : 'border-zinc-200 hover:bg-zinc-50'
        "
      >
        <span class="text-2xl">🧾</span>
        <div class="flex-1">
          <p class="font-bold text-sm">Pix</p>
          <p class="text-xs text-zinc-500">Pagamento instantâneo</p>
        </div>
        <div
          class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
          :class="form.pagamento === 'Pix' ? 'border-green-500' : 'border-zinc-300'"
        >
          <div
            v-if="form.pagamento === 'Pix'"
            class="w-2.5 h-2.5 rounded-full bg-green-500"
          ></div>
        </div>
      </div>

      <div
        @click="$emit('update:form', { ...form, pagamento: 'Cartão' })"
        class="flex items-center gap-4 p-4 border-2 rounded-2xl cursor-pointer transition"
        :class="
          form.pagamento === 'Cartão'
            ? 'border-green-500 bg-green-50'
            : 'border-zinc-200 hover:bg-zinc-50'
        "
      >
        <span class="text-2xl">💳</span>
        <div class="flex-1">
          <p class="font-bold text-sm">Cartão</p>
          <p class="text-xs text-zinc-500">Crédito ou débito</p>
        </div>
        <div
          class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
          :class="form.pagamento === 'Cartão' ? 'border-green-500' : 'border-zinc-300'"
        >
          <div
            v-if="form.pagamento === 'Cartão'"
            class="w-2.5 h-2.5 rounded-full bg-green-500"
          ></div>
        </div>
      </div>

      <div
        @click="$emit('update:form', { ...form, pagamento: 'Dinheiro' })"
        class="flex items-center gap-4 p-4 border-2 rounded-2xl cursor-pointer transition"
        :class="
          form.pagamento === 'Dinheiro'
            ? 'border-green-500 bg-green-50'
            : 'border-zinc-200 hover:bg-zinc-50'
        "
      >
        <span class="text-2xl">💵</span>
        <div class="flex-1">
          <p class="font-bold text-sm">Dinheiro</p>
          <p class="text-xs text-zinc-500">Pague na entrega</p>
        </div>
        <div
          class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
          :class="form.pagamento === 'Dinheiro' ? 'border-green-500' : 'border-zinc-300'"
        >
          <div
            v-if="form.pagamento === 'Dinheiro'"
            class="w-2.5 h-2.5 rounded-full bg-green-500"
          ></div>
        </div>
      </div>
    </div>

    <!-- PIX -->
    <div
      v-if="form.pagamento === 'Pix'"
      class="border-2 border-green-200 bg-green-50 rounded-2xl p-4 space-y-4"
    >
      <p class="text-sm font-bold text-center text-green-700">
        Escaneie o QR Code para pagar
      </p>

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
            {{ pixCopiado ? "✓ Copiado" : "Copiar" }}
          </button>
        </div>
      </div>

      <p class="text-xs text-center text-zinc-400">
        Após o pagamento, seu pedido será confirmado automaticamente
      </p>
    </div>

    <!-- DINHEIRO -->
    <div
      v-if="form.pagamento === 'Dinheiro'"
      class="border-2 border-zinc-200 bg-zinc-50 rounded-2xl p-4 space-y-3"
    >
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

      <div
        v-if="troco"
        class="bg-white border border-zinc-200 rounded-2xl p-3 flex justify-between items-center"
      >
        <span class="text-sm text-zinc-500">Seu troco será</span>
        <span class="text-lg font-black text-green-600">R$ {{ troco }}</span>
      </div>

      <div
        v-if="trocoPara && !troco && parseFloat(trocoPara) > 0"
        class="text-xs text-red-500"
      >
        ⚠️ O valor informado é menor que o total do pedido
      </div>
    </div>

    <!-- CARTÃO -->
    <div
      v-if="form.pagamento === 'Cartão'"
      class="border-2 border-zinc-200 bg-zinc-50 rounded-2xl p-4 space-y-3"
    >
      <p class="text-sm font-bold text-zinc-700">Tipo do cartão</p>

      <div class="grid grid-cols-2 gap-3">
        <div
          @click="$emit('update:tipoCartao', 'credito')"
          class="flex items-center justify-center gap-2 p-4 border-2 rounded-2xl cursor-pointer transition"
          :class="
            tipoCartao === 'credito'
              ? 'border-green-500 bg-green-50'
              : 'border-zinc-200 bg-white hover:bg-zinc-100'
          "
        >
          <span class="text-xl">💳</span>
          <span class="font-bold text-sm">Crédito</span>
        </div>

        <div
          @click="$emit('update:tipoCartao', 'debito')"
          class="flex items-center justify-center gap-2 p-4 border-2 rounded-2xl cursor-pointer transition"
          :class="
            tipoCartao === 'debito'
              ? 'border-green-500 bg-green-50'
              : 'border-zinc-200 bg-white hover:bg-zinc-100'
          "
        >
          <span class="text-xl">💳</span>
          <span class="font-bold text-sm">Débito</span>
        </div>
      </div>

      <p class="text-xs text-center text-zinc-400">
        A maquininha será apresentada na entrega
      </p>
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
            <button class="w-full text-sm text-zinc-500" @click="$emit('prev')">Voltar</button>
            <button
                v-if="form.pagamento === 'Dinheiro' || (form.pagamento === 'Cartão' && tipoCartao)"
                class="w-full bg-green-500 hover:bg-green-600 text-white py-4 rounded-2xl font-bold text-lg disabled:opacity-60 disabled:cursor-not-allowed"
                :disabled="carregando"
                @click="$emit('confirmar')"
            >
                <span v-if="carregando">⏳ Confirmando pedido...</span>
                <span v-else>Confirmar Pedido</span>
            </button>
        </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";

const props = defineProps({
  form: Object,
  total: String,
  tipoCartao: String,
  carregando: Boolean,
});

defineEmits(["update:form", "update:tipoCartao", "prev", "confirmar"]);


const pixCopiado = ref(false);
const trocoPara = ref("");

const troco = computed(() => {
  const valor = parseFloat(trocoPara.value);
  const totalNum = parseFloat(props.total);
  if (!valor || valor <= totalNum) return null;
  return (valor - totalNum).toFixed(2);
});

function copiarPix() {
  const texto = "exemplo@burguerhouse.com";
  if (navigator.clipboard) {
    navigator.clipboard.writeText(texto);
  } else {
    const el = document.createElement("textarea");
    el.value = texto;
    document.body.appendChild(el);
    el.select();
    document.execCommand("copy");
    document.body.removeChild(el);
  }
  pixCopiado.value = true;
  setTimeout(() => (pixCopiado.value = false), 3000);
}
</script>
