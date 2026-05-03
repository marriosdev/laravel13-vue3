<script setup>
import { ref, onMounted } from 'vue'
import DashboardService from '../services/DashboardService'

const stats = ref({
  total_produtos: 0,
  valor_total_estoque: 0,
  vendas_totais: 0,
  lucro_total: 0,
  vendas_recentes: [],
  produtos_estoque_baixo: []
})

const loading = ref(true)

const formatCurrency = (value) => {
  return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value || 0)
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('pt-BR').format(date)
}

const fetchDashboardData = async () => {
  try {
    const response = await DashboardService.getEstatisticas()
    stats.value = response.data
  } catch (error) {
    console.error("Erro ao buscar estatísticas", error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchDashboardData()
})
</script>

<template>
  <div v-if="loading" class="d-flex justify-center mt-10">
    <v-progress-circular indeterminate color="primary"></v-progress-circular>
  </div>
  
  <template v-else>
    <v-row>
      <v-col cols="12" md="3">
        <v-card color="success" dark>
          <v-card-text>
            <div class="text-h6 mb-2">Total de Vendas</div>
            <div class="text-h4">{{ formatCurrency(stats.vendas_totais) }}</div>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" md="3">
        <v-card color="info" dark>
          <v-card-text>
            <div class="text-h6 mb-2">Lucro Total</div>
            <div class="text-h4">{{ formatCurrency(stats.lucro_total) }}</div>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" md="3">
        <v-card color="warning" dark>
          <v-card-text>
            <div class="text-h6 mb-2">Valor em Estoque</div>
            <div class="text-h4">{{ formatCurrency(stats.valor_total_estoque) }}</div>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" md="3">
        <v-card color="primary" dark>
          <v-card-text>
            <div class="text-h6 mb-2">Total de Produtos</div>
            <div class="text-h4">{{ stats.total_produtos }}</div>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <v-row class="mt-4">
      <v-col cols="12">
        <v-card>
          <v-card-title>Últimas Vendas</v-card-title>
          <v-table>
            <thead>
              <tr>
                <th class="text-left">ID</th>
                <th class="text-left">Cliente</th>
                <th class="text-left">Itens</th>
                <th class="text-left">Data</th>
                <th class="text-left">Valor Total</th>
                <th class="text-left">Lucro</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="stats.vendas_recentes.length === 0">
                <td colspan="6" class="text-center">Nenhuma venda encontrada</td>
              </tr>
              <tr v-for="venda in stats.vendas_recentes" :key="venda.id">
                <td>#{{ venda.id }}</td>
                <td>{{ venda.cliente }}</td>
                <td><small>{{ venda.itens.map(i => i.quantidade + 'x ' + i.produto_nome).join(', ') }}</small></td>
                <td>{{ formatDate(venda.criado_em) }}</td>
                <td>{{ formatCurrency(venda.valor_total) }}</td>
                <td><v-chip color="success" size="small">{{ formatCurrency(venda.lucro_total) }}</v-chip></td>
              </tr>
            </tbody>
          </v-table>
        </v-card>
      </v-col>
    </v-row>
  </template>
</template>
