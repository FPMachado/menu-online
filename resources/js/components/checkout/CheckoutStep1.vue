<template>
  <div class="p-6 space-y-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <!-- TELEFONE -->
      <div>
        <label class="text-sm font-semibold">WhatsApp</label>
        <input
        :value="form.whatsapp"
        @input="$emit('update:form', { ...form, whatsapp: maskTelefone($event.target.value) })"
        type="text"
        maxlength="15"
        class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
        placeholder="(00) 00000-0000"
        />
      </div>

      <!-- NOME -->
      <div>
        <label class="text-sm font-semibold">Seu nome</label>
        <input
          :value="form.nome"
          @input="$emit('update:form', { ...form, nome: $event.target.value })"
          type="text"
          :readonly="clienteExiste"
          :class="[
            clienteExiste ? 'bg-zinc-100 cursor-not-allowed' : '',
            erroNome ? 'border-red-500 shake' : '',
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
      @click="$emit('next')"
    >
      Continuar
    </button>
  </div>
</template>

<script setup>
import { maskTelefone } from "@/utils/formatters";

defineProps({
  form: Object,
  clienteExiste: Boolean,
  erroNome: Boolean,
});

defineEmits(["update:form", "next"]);
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
