import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../views/Dashboard.vue'
import Produtos from '../views/Produtos.vue'
import Vendas from '../views/Vendas.vue'
import Compras from '../views/Compras.vue'

const routes = [
  { path: '/', component: Dashboard },
  { path: '/produtos', component: Produtos },
  { path: '/vendas', component: Vendas },
  { path: '/compras', component: Compras },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
