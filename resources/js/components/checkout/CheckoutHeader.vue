<template>
  <div class="p-6 border-b flex justify-between items-center">
    <div>
      <h2 class="text-2xl font-black">Finalizar Pedido</h2>
      <p class="text-sm text-zinc-500">Etapa {{ step }} de 3</p>
    </div>

    <button
      @click="$emit('fechar')"
      class="w-10 h-10 rounded-full bg-zinc-100 hover:bg-zinc-200"
    >
      ✕
    </button>
  </div>
</template>

<script setup>
defineProps({
  step: Number,
});

defineEmits(["fechar"]);
</script>
