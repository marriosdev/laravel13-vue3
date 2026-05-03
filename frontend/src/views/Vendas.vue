<script setup>
import { ref, onMounted } from 'vue'
import VendaService from '../services/VendaService'
import ProdutoService from '../services/ProdutoService'
import { useNotification } from '../composables/useNotification'

const { notifySuccess, notifyError, handleValidationError } = useNotification()

const vendas = ref([])
const produtos = ref([])
const loading = ref(true)
const page = ref(1)
const totalPages = ref(1)

const dialog = ref(false)
const confirmDialog = ref(false)
const itemToCancel = ref(null)
const saving = ref(false)
const form = ref({
  cliente: '',
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
    const [vendasRes, produtosRes] = await Promise.all([
      VendaService.getAll(page.value),
      ProdutoService.getForSelect()
    ])
    vendas.value = vendasRes.data.data
    totalPages.value = vendasRes.data.last_page
    produtos.value = produtosRes.data.data || produtosRes.data
  } catch (error) {
    console.error("Erro ao buscar dados", error)
  } finally {
    loading.value = false
  }
}

const openDialog = () => {
  form.value = {
    cliente: '',
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

const saveVenda = async () => {
  try {
    saving.value = true
    await VendaService.create(form.value)
    notifySuccess("Venda registrada com sucesso!")
    dialog.value = false
    fetchData()
  } catch (error) {
    console.error("Erro ao registrar venda", error)
    handleValidationError(error)
  } finally {
    saving.value = false
  }
}

const onProductSelect = (item) => {
  const selected = produtos.value.find(p => p.id === item.id)
  if (selected) {
    item.preco_unitario = selected.preco_venda
  }
}

const confirmCancel = (id) => {
  itemToCancel.value = id
  confirmDialog.value = true
}

const cancelarVenda = async () => {
  if (!itemToCancel.value) return
  try {
    loading.value = true
    await VendaService.cancel(itemToCancel.value)
    notifySuccess("Venda cancelada com sucesso!")
    confirmDialog.value = false
    itemToCancel.value = null
    await fetchData()
  } catch (error) {
    console.error("Erro ao cancelar venda", error)
    handleValidationError(error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchData()
})
</script>

<template>
  <div>
    <div class="d-flex justify-space-between align-center mb-4">
      <h2>Vendas</h2>
      <v-btn color="primary" @click="openDialog" prepend-icon="mdi-plus">Registrar Venda</v-btn>
    </div>
    
    <v-card>
      <v-card-text v-if="loading" class="d-flex justify-center">
        <v-progress-circular indeterminate color="primary"></v-progress-circular>
      </v-card-text>
      
      <v-table v-else>
        <thead>
          <tr>
            <th class="text-left">ID</th>
            <th class="text-left">Cliente</th>
            <th class="text-left">Data</th>
            <th class="text-left">Valor Total</th>
            <th class="text-left">Lucro</th>
            <th class="text-left">Status</th>
            <th class="text-left">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="vendas.length === 0">
            <td colspan="7" class="text-center">Nenhuma venda encontrada</td>
          </tr>
          <tr v-for="venda in vendas" :key="venda.id">
            <td>#{{ venda.id }}</td>
            <td>{{ venda.cliente }}</td>
            <td>{{ formatDate(venda.criado_em) }}</td>
            <td>{{ formatCurrency(venda.valor_total) }}</td>
            <td><v-chip color="success" size="small">{{ formatCurrency(venda.lucro_total) }}</v-chip></td>
            <td>
              <v-chip :color="venda.cancelada ? 'error' : 'success'" size="small">
                {{ venda.cancelada ? 'Cancelada' : 'Ativa' }}
              </v-chip>
            </td>
            <td>
              <v-btn v-if="!venda.cancelada" icon color="error" variant="text" size="small" @click="confirmCancel(venda.id)" title="Cancelar Venda">
                <v-icon>mdi-cancel</v-icon>
              </v-btn>
            </td>
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
          <span class="text-h5">Registrar Venda</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field v-model="form.cliente" label="Nome do Cliente" required></v-text-field>
              </v-col>
            </v-row>
            <v-divider class="my-4"></v-divider>
            <div class="text-h6 mb-2">Produtos (Baixa no Estoque)</div>
            <v-row v-for="(item, index) in form.produtos" :key="index" align="center">
              <v-col cols="12" md="5">
                <v-select
                  v-model="item.id"
                  :items="produtos"
                  item-title="nome"
                  item-value="id"
                  label="Produto"
                  required
                  @update:model-value="onProductSelect(item)"
                ></v-select>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field v-model="item.quantidade" label="Qtd" type="number" required></v-text-field>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field v-model="item.preco_unitario" label="Preço Venda" type="number" prefix="R$" required></v-text-field>
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
          <v-btn color="blue-darken-1" variant="text" @click="saveVenda" :loading="saving">Salvar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="confirmDialog" max-width="450px">
      <v-card>
        <v-card-title class="text-h5">Confirmar Cancelamento</v-card-title>
        <v-card-text>Deseja realmente cancelar esta venda? Os produtos serão retornados ao estoque automaticamente.</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey-darken-1" variant="text" @click="confirmDialog = false">Não, manter</v-btn>
          <v-btn color="error" variant="text" @click="cancelarVenda" :loading="loading">Sim, cancelar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
