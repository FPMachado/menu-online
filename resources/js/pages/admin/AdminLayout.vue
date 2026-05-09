<template>
    <div class="min-h-screen bg-zinc-50 flex">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white border-r border-zinc-200 flex flex-col fixed h-full">
            <div class="p-6 border-b border-zinc-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-orange-400 flex items-center justify-center text-xl">
                        🍔
                    </div>
                    <div>
                        <h1 class="font-black text-sm">Burger House</h1>
                        <p class="text-xs text-zinc-500">Painel Admin</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 p-4 space-y-1">
                <RouterLink
                    to="/admin"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition"
                    :class="$route.path === '/admin' ? 'bg-red-50 text-red-500' : 'text-zinc-600 hover:bg-zinc-50'"
                >
                    📊 Dashboard
                </RouterLink>

                <RouterLink
                    to="/admin/pedidos"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition"
                    :class="$route.path === '/admin/pedidos' ? 'bg-red-50 text-red-500' : 'text-zinc-600 hover:bg-zinc-50'"
                >
                    🛵 Pedidos
                </RouterLink>

                <RouterLink
                    to="/admin/cardapio"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition"
                    :class="$route.path === '/admin/cardapio' ? 'bg-red-50 text-red-500' : 'text-zinc-600 hover:bg-zinc-50'"
                >
                    🍔 Cardápio
                </RouterLink>

                <RouterLink
                    to="/admin/configuracoes"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition"
                    :class="$route.path === '/admin/configuracoes' ? 'bg-red-50 text-red-500' : 'text-zinc-600 hover:bg-zinc-50'"
                >
                    ⚙️ Configurações
                </RouterLink>
            </nav>

            <div class="p-4 border-t border-zinc-200">
                <button
                    @click="logout"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold text-zinc-600 hover:bg-red-50 hover:text-red-500 transition"
                >
                    🚪 Sair
                </button>
            </div>
        </aside>

        <!-- CONTEÚDO -->
        <main class="flex-1 ml-64">
            <RouterView />
        </main>

    </div>
</template>

<script setup>
import { RouterLink, RouterView, useRouter } from 'vue-router'

const router = useRouter()

async function logout() {
    await fetch('/api/logout', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('admin_token')}`,
            'Accept': 'application/json',
        },
    })

    localStorage.removeItem('admin_token')
    router.push('/admin/login')
}
</script>
