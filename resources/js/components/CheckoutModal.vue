<template>
  <div
    v-if="aberto"
    class="fixed inset-0 z-[60] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4"
  >
    <div
      class="bg-white w-full max-w-xl rounded-3xl shadow-2xl max-h-[95vh] overflow-y-auto"
    >
      <CheckoutHeader :step="step" @fechar="$emit('fechar')" />

      <CheckoutStep1
        v-if="step === 1"
        :form="form"
        :clienteExiste="clienteExiste"
        :erroNome="erroNome"
        @update:form="form = $event"
        @next="nextStep"
      />

      <CheckoutStep2
        v-if="step === 2"
        :form="form"
        :tipoEntrega="tipoEntrega"
        :enderecos="enderecos"
        :enderecoSelecionado="enderecoSelecionado"
        @update:form="form = $event"
        @update:tipoEntrega="tipoEntrega = $event"
        @selecionarEndereco="selecionarEndereco"
        @next="nextStep"
        @prev="prevStep"
      />

      <CheckoutStep3
        v-if="step === 3"
        :form="form"
        :total="total"
        :tipoCartao="tipoCartao"
        @update:form="form = $event"
        @update:tipoCartao="tipoCartao = $event"
        @prev="prevStep"
        @confirmar="confirmarPedido"
      />
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, watch } from "vue";
import CheckoutHeader from "./checkout/CheckoutHeader.vue";
import CheckoutStep1 from "./checkout/CheckoutStep1.vue";
import CheckoutStep2 from "./checkout/CheckoutStep2.vue";
import CheckoutStep3 from "./checkout/CheckoutStep3.vue";

const props = defineProps({
  aberto: Boolean,
  total: String,
});

defineEmits(["fechar"]);

const form = ref({
  whatsapp: "",
  nome: "",
  cep: "",
  rua: "",
  bairro: "",
  cidade: "",
  numero: "",
  complemento: "",
  referencia: "",
  pagamento: "Pix",
  obs: "",
});

const cliente = ref(null);
const clienteExiste = ref(null);
const enderecos = ref([]);
const enderecoSelecionado = ref(null);
const step = ref(1);
const erroNome = ref(false);
const tipoEntrega = ref(null);
const tipoCartao = ref(null);
const bloqueiaWatchCep = ref(false);

/* BUSCA CLIENTE */
watch(
  () => form.value.whatsapp,
  async (novo) => {
    const telefoneLimpo = novo ? novo.replace(/\D/g, "") : "";

    if (!telefoneLimpo || telefoneLimpo.length < 11) {
      clienteExiste.value = null;
      cliente.value = null;
      enderecos.value = [];
      form.value.nome = "";
      limparEndereco();
      erroNome.value = false;
      return;
    }

    try {
      const res = await fetch(`/api/buscar-cliente?telefone=${telefoneLimpo}`);
      const data = await res.json();

      if (data.existe) {
        clienteExiste.value = true;
        cliente.value = data.cliente;
        form.value.nome = data.cliente.nome;
        enderecos.value = data.cliente.enderecos ?? [];
      } else {
        clienteExiste.value = false;
        cliente.value = null;
      }
    } catch (err) {
      console.error(err);
    }
  }
);

/* CEP */
watch(
  () => form.value.cep,
  async (novo) => {
    if (bloqueiaWatchCep.value) return;

    const cepLimpo = novo ? novo.replace(/\D/g, "") : "";
    if (cepLimpo.length !== 8) return;

    try {
      const res = await fetch(`https://viacep.com.br/ws/${cepLimpo}/json/`);
      const data = await res.json();

      if (data.erro) return;

      form.value.rua = data.logradouro || "";
      form.value.bairro = data.bairro || "";
      form.value.cidade = data.localidade || "";
      form.value.numero = "";
      enderecoSelecionado.value = null;
    } catch (err) {
      console.error(err);
    }
  }
);

function selecionarEndereco(end) {
  bloqueiaWatchCep.value = true;
  enderecoSelecionado.value = end;
  form.value.rua = end.rua || "";
  form.value.numero = end.numero || "";
  form.value.cep = end.cep || "";
  form.value.bairro = end.bairro || "";
  form.value.cidade = end.cidade || "";
  form.value.complemento = end.complemento || "";
  form.value.referencia = end.referencia || "";

  requestAnimationFrame(() => {
    bloqueiaWatchCep.value = false;
  });
}

function limparEndereco() {
  form.value.rua = "";
  form.value.numero = "";
  form.value.cep = "";
  form.value.bairro = "";
  form.value.cidade = "";
}

function nextStep() {
  if (step.value === 1) {
    if (!form.value.nome || form.value.nome.trim().length < 3) {
      erroNome.value = true;
      return;
    }
  }

  if (step.value === 2 && tipoEntrega.value !== "entrega") {
    step.value = 3;
    return;
  }

  erroNome.value = false;
  if (step.value < 3) step.value++;
}

function prevStep() {
  if (step.value === 3 && tipoEntrega.value !== "entrega") {
    step.value = 2;
    return;
  }
  if (step.value > 1) step.value--;
}

function confirmarPedido() {
  console.log("Pedido confirmado!", form);
}
</script>

<style scoped>
@keyframes shake {
  0%,
  100% {
    transform: translateX(0);
  }
  25% {
    transform: translateX(-4px);
  }
  50% {
    transform: translateX(4px);
  }
  75% {
    transform: translateX(-4px);
  }
}

.shake {
  animation: shake 0.25s ease;
}
</style>
