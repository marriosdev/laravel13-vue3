<script setup>
import { ref, onMounted } from 'vue'
import CompraService from '../services/CompraService'
import ProdutoService from '../services/ProdutoService'
import { useNotification } from '../composables/useNotification'

const { notifySuccess, notifyError, handleValidationError } = useNotification()

const compras = ref([])
const produtos = ref([])
const loading = ref(true)
const page = ref(1)
const totalPages = ref(1)

const dialog = ref(false)
const saving = ref(false)
const form = ref({
  fornecedor: '',
  produtos: []
})

const defaultItem = { id: null, quantidade: 1, preco_unitario: 0 }

const formatCurrency = (value) => {
  return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value || 0)
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('pt-BR').format(date)
}

const fetchData = async () => {
  try {
    loading.value = true
    const [comprasRes, produtosRes] = await Promise.all([
      CompraService.getAll(page.value),
      ProdutoService.getForSelect()
    ])
    compras.value = comprasRes.data.data
    totalPages.value = comprasRes.data.last_page
    produtos.value = produtosRes.data.data || produtosRes.data
  } catch (error) {
    console.error("Erro ao buscar dados", error)
  } finally {
    loading.value = false
  }
}

const openDialog = () => {
  form.value = {
    fornecedor: '',
    produtos: [{ ...defaultItem }]
  }
  dialog.value = true
}

const addProductItem = () => {
  form.value.produtos.push({ ...defaultItem })
}

const removeProductItem = (index) => {
  form.value.produtos.splice(index, 1)
}

const saveCompra = async () => {
  try {
    saving.value = true
    await CompraService.create(form.value)
    notifySuccess("Compra registrada com sucesso!")
    dialog.value = false
    fetchData()
  } catch (error) {
    console.error("Erro ao registrar compra", error)
    handleValidationError(error)
  } finally {
    saving.value = false
  }
}

onMounted(() => {
  fetchData()
})
</script>

<template>
  <div>
    <div class="d-flex justify-space-between align-center mb-4">
      <h2>Compras</h2>
      <v-btn color="primary" @click="openDialog" prepend-icon="mdi-plus">Registrar Compra</v-btn>
    </div>

    <v-card>
      <v-card-text v-if="loading" class="d-flex justify-center">
        <v-progress-circular indeterminate color="primary"></v-progress-circular>
      </v-card-text>
      
      <v-table v-else>
        <thead>
          <tr>
            <th class="text-left">ID</th>
            <th class="text-left">Fornecedor</th>
            <th class="text-left">Data</th>
            <th class="text-left">Valor Total</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="compras.length === 0">
            <td colspan="4" class="text-center">Nenhuma compra encontrada</td>
          </tr>
          <tr v-for="compra in compras" :key="compra.id">
            <td>#{{ compra.id }}</td>
            <td>{{ compra.fornecedor }}</td>
            <td>{{ formatDate(compra.criado_em) }}</td>
            <td>{{ formatCurrency(compra.custo_total) }}</td>
          </tr>
        </tbody>
      </v-table>
    </v-card>

    <div class="mt-4">
      <v-pagination
        v-model="page"
        :length="totalPages"
        @update:model-value="fetchData"
        color="primary"
      ></v-pagination>
    </div>

    <v-dialog v-model="dialog" max-width="600px">
      <v-card>
        <v-card-title>
          <span class="text-h5">Registrar Compra</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field v-model="form.fornecedor" label="Nome do Fornecedor" required></v-text-field>
              </v-col>
            </v-row>
            <v-divider class="my-4"></v-divider>
            <div class="text-h6 mb-2">Produtos</div>
            <v-row v-for="(item, index) in form.produtos" :key="index" align="center">
              <v-col cols="12" md="5">
                <v-select
                  v-model="item.id"
                  :items="produtos"
                  item-title="nome"
                  item-value="id"
                  label="Produto"
                  required
                ></v-select>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field v-model="item.quantidade" label="Qtd" type="number" required></v-text-field>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field v-model="item.preco_unitario" label="Preço Custo" type="number" prefix="R$" required></v-text-field>
              </v-col>
              <v-col cols="12" md="1">
                <v-btn icon color="error" variant="text" size="small" @click="removeProductItem(index)" :disabled="form.produtos.length === 1">
                  <v-icon>mdi-delete</v-icon>
                </v-btn>
              </v-col>
            </v-row>
            <v-btn color="secondary" variant="tonal" size="small" @click="addProductItem" class="mt-2">
              <v-icon start>mdi-plus</v-icon> Adicionar Item
            </v-btn>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue-darken-1" variant="text" @click="dialog = false">Cancelar</v-btn>
          <v-btn color="blue-darken-1" variant="text" @click="saveCompra" :loading="saving">Salvar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
