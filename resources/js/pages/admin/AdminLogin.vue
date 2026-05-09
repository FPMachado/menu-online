<template>
    <div class="min-h-screen bg-zinc-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-3xl shadow-xl p-8">

            <div class="text-center mb-8">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-red-500 to-orange-400 flex items-center justify-center text-3xl mx-auto mb-4">
                    🍔
                </div>
                <h1 class="text-2xl font-black">Painel Admin</h1>
                <p class="text-sm text-zinc-500 mt-1">Entre com suas credenciais</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="text-sm font-semibold">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
                        placeholder="seu@email.com"
                    />
                </div>

                <div>
                    <label class="text-sm font-semibold">Senha</label>
                    <input
                        v-model="form.password"
                        type="password"
                        class="mt-2 w-full bg-[#fafafa] border border-zinc-200 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition"
                        placeholder="••••••••"
                    />
                </div>

                <button
                    @click="login"
                    class="w-full bg-red-500 hover:bg-red-600 text-white py-4 rounded-2xl font-bold text-lg transition"
                >
                    Entrar
                </button>
            </div>

        </div>
    </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = reactive({
    email: '',
    password: '',
})

async function login() {
    try {
        const res = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                email: form.email,
                password: form.password,
            }),
        })

        const data = await res.json()

        if (res.ok) {
            localStorage.setItem('admin_token', data.token)
            router.push('/admin')
        } else {
            alert('Email ou senha incorretos!')
        }
    } catch (err) {
        console.error(err)
    }
}
</script>
