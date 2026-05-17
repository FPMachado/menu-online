<template>
    <div
        v-if="aberto"
        class="fixed inset-0 z-[60] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4"
    >
        <div
            class="relative bg-white w-full max-w-xl rounded-3xl shadow-2xl max-h-[95vh] overflow-y-auto"
        >
            <!-- LOADING OVERLAY -->
            <div
                v-if="carregando"
                class="absolute inset-0 z-10 bg-white/90 backdrop-blur-sm rounded-3xl flex flex-col items-center justify-center gap-4"
            >
                <div class="w-14 h-14 rounded-full border-4 border-zinc-200 border-t-green-500 animate-spin"></div>
                <div class="text-center">
                    <p class="font-black text-lg">Confirmando seu pedido</p>
                    <p class="text-sm text-zinc-500 mt-1">Aguarde um momento...</p>
                </div>
            </div>

            <CheckoutHeader
                v-if="!pedidoConfirmado"
                :step="step"
                @fechar="$emit('fechar')"
            />

            <CheckoutSucesso
                v-if="pedidoConfirmado"
                :pedido="pedidoConfirmado"
                @fechar="resetar(); $emit('fechar')"
            />

            <CheckoutStep1
                v-if="!pedidoConfirmado && step === 1"
                :form="form"
                :clienteExiste="clienteExiste"
                :erroNome="erroNome"
                @update:form="form = $event"
                @next="nextStep"
            />

            <CheckoutStep2
                v-if="!pedidoConfirmado && step === 2"
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
                v-if="!pedidoConfirmado && step === 3"
                :form="form"
                :total="total"
                :tipoCartao="tipoCartao"
                :tipoEntrega="tipoEntrega"
                :configuracao="props.configuracao"
                @update:form="form = $event"
                @update:tipoCartao="tipoCartao = $event"
                @prev="prevStep"
                @confirmar="confirmarPedido"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import CheckoutHeader from "./checkout/CheckoutHeader.vue";
import CheckoutStep1 from "./checkout/CheckoutStep1.vue";
import CheckoutStep2 from "./checkout/CheckoutStep2.vue";
import CheckoutStep3 from "./checkout/CheckoutStep3.vue";
import CheckoutSucesso from "./checkout/CheckoutSucesso.vue";
import cart from '../stores/cart'

const props = defineProps({
    aberto: Boolean,
    total: String,
    configuracao: Object,
})

const emit = defineEmits(['fechar', 'pedidoCriado'])

const form = ref({
    whatsapp: "",
    nome: "",
    cep: "",
    rua: "",
    numero: "",
    bairro: "",
    cidade: "",
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
const pedidoConfirmado = ref(null);
const carregando = ref(false);

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

async function confirmarPedido() {
    carregando.value = true;
    try {
        // 1. SALVAR/BUSCAR CLIENTE
        const resCliente = await fetch("/api/clientes", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify({
                nome: form.value.nome,
                telefone: form.value.whatsapp,
            }),
        });

        const dataCliente = await resCliente.json();
        const clienteId = dataCliente.cliente.id;

        // 2. SALVAR ENDEREÇO SE FOR ENTREGA E ENDEREÇO NOVO
        let enderecoId = enderecoSelecionado.value?.id ?? null;

        if (tipoEntrega.value === "entrega" && !enderecoSelecionado.value) {
            const resEndereco = await fetch(`/api/clientes/${clienteId}/enderecos`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                body: JSON.stringify({
                    cep: form.value.cep,
                    rua: form.value.rua,
                    numero: form.value.numero,
                    bairro: form.value.bairro,
                    cidade: form.value.cidade,
                    complemento: form.value.complemento,
                    referencia: form.value.referencia,
                }),
            });

            const dataEndereco = await resEndereco.json();
            enderecoId = dataEndereco.endereco.id;
        }

        // 3. CRIAR PEDIDO
        const resPedido = await fetch("/api/pedidos", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify({
                cliente_id: clienteId,
                endereco_id: enderecoId,
                pagamento: form.value.pagamento,
                total: props.total,
                tipo_entrega: tipoEntrega.value,
                status: "pendente",
                itens: cart.state.items.map(item => ({
                    produto_id: item.id,
                    nome: item.nome,
                    preco: parseFloat(item.preco.replace(',', '.')),
                    quantidade: item.qtd,
                    subtotal: parseFloat(item.preco.replace(',', '.')) * item.qtd,
                })),
            }),
        });

        const dataPedido = await resPedido.json();

        if (dataPedido.success) {
            pedidoConfirmado.value = dataPedido.pedido
            localStorage.setItem('pedido_ativo_id', dataPedido.pedido.id)
            emit('pedidoCriado', dataPedido.pedido.id)
        }
    } catch (err) {
        console.error("Erro ao confirmar pedido:", err);
    }finally {
        carregando.value = false;
    }
}

function resetar() {
    pedidoConfirmado.value = null
    step.value = 1
    tipoEntrega.value = null
    tipoCartao.value = null
    enderecoSelecionado.value = null
    form.value = {
        whatsapp: '',
        nome: '',
        cep: '',
        rua: '',
        numero: '',
        bairro: '',
        cidade: '',
        complemento: '',
        referencia: '',
        pagamento: 'Pix',
        obs: '',
    }
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
