<template>
	<div class="p-6 space-y-5">
		<!-- TIPO DE ENTREGA -->
		<div class="space-y-3">
			<p class="text-sm font-bold text-zinc-700">
				Como quer receber seu pedido?
			</p>

			<div
				@click="$emit('update:tipoEntrega', 'entrega')"
				class="flex items-center gap-4 p-4 border-2 rounded-2xl cursor-pointer transition"
				:class="
					tipoEntrega === 'entrega'
						? 'border-red-500 bg-red-50'
						: 'border-zinc-200 hover:bg-zinc-50'
				"
			>
				<span class="text-2xl">🛵</span>

				<div>
					<p class="font-bold text-sm">Entrega</p>
					<p class="text-xs text-zinc-500">Receba em casa</p>
				</div>

				<div
					class="ml-auto w-5 h-5 rounded-full border-2 flex items-center justify-center"
					:class="
						tipoEntrega === 'entrega'
							? 'border-red-500'
							: 'border-zinc-300'
					"
				>
					<div
						v-if="tipoEntrega === 'entrega'"
						class="w-2.5 h-2.5 rounded-full bg-red-500"
					></div>
				</div>
			</div>

			<div
				@click="$emit('update:tipoEntrega', 'retirada')"
				class="flex items-center gap-4 p-4 border-2 rounded-2xl cursor-pointer transition"
				:class="
					tipoEntrega === 'retirada'
						? 'border-red-500 bg-red-50'
						: 'border-zinc-200 hover:bg-zinc-50'
				"
			>
				<span class="text-2xl">🏪</span>

				<div>
					<p class="font-bold text-sm">Retirada</p>
					<p class="text-xs text-emerald-600 font-semibold">Grátis</p>
				</div>

				<div
					class="ml-auto w-5 h-5 rounded-full border-2 flex items-center justify-center"
					:class="
						tipoEntrega === 'retirada'
							? 'border-red-500'
							: 'border-zinc-300'
					"
				>
					<div
						v-if="tipoEntrega === 'retirada'"
						class="w-2.5 h-2.5 rounded-full bg-red-500"
					></div>
				</div>
			</div>

			<div
				@click="$emit('update:tipoEntrega', 'local')"
				class="flex items-center gap-4 p-4 border-2 rounded-2xl cursor-pointer transition"
				:class="
					tipoEntrega === 'local'
						? 'border-red-500 bg-red-50'
						: 'border-zinc-200 hover:bg-zinc-50'
				"
			>
				<span class="text-2xl">🍽️</span>

				<div>
					<p class="font-bold text-sm">Consumir no local</p>
					<p class="text-xs text-zinc-500">Comer aqui</p>
				</div>

				<div
					class="ml-auto w-5 h-5 rounded-full border-2 flex items-center justify-center"
					:class="
						tipoEntrega === 'local'
							? 'border-red-500'
							: 'border-zinc-300'
					"
				>
					<div
						v-if="tipoEntrega === 'local'"
						class="w-2.5 h-2.5 rounded-full bg-red-500"
					></div>
				</div>
			</div>
		</div>

		<!-- ENDEREÇO — só aparece se escolher entrega -->
		<div v-if="tipoEntrega === 'entrega'" class="space-y-4">
			<!-- ENDEREÇOS SALVOS -->
			<div v-if="enderecos.length">
				<label class="text-sm font-bold mb-2 block">
					Seus endereços:
				</label>

				<select
					@change="
						$emit(
							'selecionarEndereco',
							enderecos.find(
								(e) => e.id == $event.target.value
							)
						)
					"
					class="w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
				>
					<option style="border-radios: 5px" value="">
						Selecione um endereço...
					</option>

					<option
						v-for="end in enderecos"
						:key="end.id"
						:value="end.id"
						:selected="enderecoSelecionado?.id === end.id"
					>
						🏠 {{ end.rua }}, {{ end.numero }} -
						{{ end.bairro }}
					</option>
				</select>
			</div>

			<!-- FORMULÁRIO -->
			<div class="grid md:grid-cols-2 gap-4">
				<div>
					<label class="text-sm font-semibold">CEP</label>

					<input
						:value="form.cep"
						@input="
							$emit('update:form', {
								...form,
								cep: maskCep($event.target.value),
							})
						"
						type="text"
						class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
						placeholder="00000-000"
					/>
				</div>

				<div>
					<label class="text-sm font-semibold">Rua</label>

					<input
						:value="form.rua"
						@input="
							$emit('update:form', {
								...form,
								rua: $event.target.value,
							})
						"
						type="text"
						class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
					/>
				</div>

				<div>
					<label class="text-sm font-semibold">Bairro</label>

					<input
						:value="form.bairro"
						@input="
							$emit('update:form', {
								...form,
								bairro: $event.target.value,
							})
						"
						type="text"
						class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
					/>
				</div>

				<div>
					<label class="text-sm font-semibold">Cidade</label>

					<input
						:value="form.cidade"
						@input="
							$emit('update:form', {
								...form,
								cidade: $event.target.value,
							})
						"
						type="text"
						class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
					/>
				</div>

				<div>
					<label class="text-sm font-semibold">Número</label>

					<input
						:value="form.numero"
						@input="
							$emit('update:form', {
								...form,
								numero: $event.target.value,
							})
						"
						type="text"
						class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
					/>
				</div>

				<div>
					<label class="text-sm font-semibold">
						Complemento
					</label>

					<input
						:value="form.complemento"
						@input="
							$emit('update:form', {
								...form,
								complemento: $event.target.value,
							})
						"
						type="text"
						placeholder="Ex: apto 101, bloco B..."
						class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
					/>
				</div>
			</div>

			<div>
				<label class="text-sm font-semibold">Referência</label>

				<input
					:value="form.referencia"
					@input="
						$emit('update:form', {
							...form,
							referencia: $event.target.value,
						})
					"
					type="text"
					placeholder="Ex: perto da padaria..."
					class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
				/>
			</div>
		</div>

		<div class="flex gap-2 pt-2">
			<button
				class="w-full text-sm text-zinc-500"
				@click="$emit('prev')"
			>
				Voltar
			</button>

			<button
				class="w-full bg-red-500 text-white py-3 rounded-2xl font-bold disabled:opacity-40 disabled:cursor-not-allowed"
				:disabled="!tipoEntrega"
				@click="$emit('next')"
			>
				Continuar
			</button>
		</div>
	</div>
</template>

<script setup>
import { maskCep } from "@/utils/formatters";

defineProps({
	form: Object,
	tipoEntrega: String,
	enderecos: Array,
	enderecoSelecionado: Object,
});

defineEmits([
	"update:form",
	"update:tipoEntrega",
	"selecionarEndereco",
	"next",
	"prev",
]);
</script>

<style scoped>
select {
	appearance: none;
	-webkit-appearance: none;
	background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
	background-repeat: no-repeat;
	background-position: right 16px center;
	padding-right: 40px;
}
</style>
