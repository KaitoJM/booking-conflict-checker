import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import ui from '@nuxt/ui/vue-plugin'
import { useAuthStore } from './stores/auth.store'

const app = createApp(App)

app.use(createPinia())
app.use(router)

const auth = useAuthStore()
auth.hydrate()

app.use(ui)

app.mount('#app')
