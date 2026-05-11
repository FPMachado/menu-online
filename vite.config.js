import { defineConfig, loadEnv } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '')

    return {
        server: {
            host: '0.0.0.0',
            port: 5173,
            strictPort: true,
            hmr: {
                host: '192.168.1.14',
            },
        },

        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
            vue(),
            tailwindcss(),
        ],
    }
})
