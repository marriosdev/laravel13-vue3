<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
// Notification system handled by vue3-toastify

const drawer = ref(true)
const router = useRouter()

const items = [
  { title: 'Dashboard', icon: 'mdi-view-dashboard', path: '/' },
  { title: 'Produtos', icon: 'mdi-package-variant', path: '/produtos' },
  { title: 'Compras', icon: 'mdi-cart', path: '/compras' },
  { title: 'Vendas', icon: 'mdi-cash-register', path: '/vendas' },
]

const navigateTo = (path) => {
  router.push(path)
}
</script>

<template>
  <v-app>
    <v-navigation-drawer v-model="drawer" app>
      <v-list>
        <v-list-item title="Fone Ninja" subtitle="Sistema de Gestão" prepend-icon="mdi-ninja"></v-list-item>
      </v-list>

      <v-divider></v-divider>

      <v-list density="compact" nav>
        <v-list-item v-for="(item, i) in items" :key="i" :value="item" :title="item.title" :prepend-icon="item.icon"
          color="primary" @click="navigateTo(item.path)"></v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar app color="primary" dark>
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>Sistema Fone Ninja</v-toolbar-title>
    </v-app-bar>

    <v-main>
      <v-container fluid>
        <router-view></router-view>
      </v-container>
    </v-main>
  </v-app>
</template>
