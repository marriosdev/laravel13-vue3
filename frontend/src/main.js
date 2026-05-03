import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import router from './router'

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import '@mdi/font/css/materialdesignicons.css'
import Vue3Toastify from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

const vuetify = createVuetify({
  components,
  directives,
  theme: {
    defaultTheme: 'dark',
    themes: {
      dark: {
        colors: {
          primary: '#22c55e',
          secondary: '#16a34a',
        }
      }
    }
  }
})

createApp(App)
  .use(router)
  .use(vuetify)
  .use(Vue3Toastify, {
    autoClose: 3000,
    theme: 'dark',
    position: 'top-right',
  })
  .mount('#app')

