import '../css/app.css'

import { createApp } from 'vue'
import Root from './Root.vue'
import router from './router/index.js'

createApp(Root).use(router).mount('#app')
