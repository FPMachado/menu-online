import { createRouter, createWebHistory } from 'vue-router'
import MenuPublico from '../App.vue'
import AdminLogin from '../pages/admin/AdminLogin.vue'
import AdminLayout from '../pages/admin/AdminLayout.vue'
import AdminDashboard from '../pages/admin/AdminDashboard.vue'
import AdminPedidos from '../pages/admin/AdminPedidos.vue'
import AdminCardapio from '../pages/admin/AdminCardapio.vue'
import AdminConfiguracoes from '../pages/admin/AdminConfiguracoes.vue'

const routes = [
    {
        path: '/',
        component: MenuPublico,
    },
    {
        path: '/admin/login',
        component: AdminLogin,
    },
    {
        path: '/admin',
        component: AdminLayout,
        meta: { requiresAuth: true },
        children: [
            { path: '', component: AdminDashboard },
            { path: 'pedidos', component: AdminPedidos },
            { path: 'cardapio', component: AdminCardapio },
            { path: 'configuracoes', component: AdminConfiguracoes },
        ],
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth && !localStorage.getItem('admin_token')) {
        next('/admin/login')
    } else {
        next()
    }
})

export default router
