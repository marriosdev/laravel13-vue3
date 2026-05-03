<script setup>
import { ref, onMounted } from 'vue'
import ProdutoService from '../services/ProdutoService'
import { useNotification } from '../composables/useNotification'

const { notifySuccess, notifyError, handleValidationError } = useNotification()

const produtos = ref([])
const loading = ref(true)
const page = ref(1)
const totalPages = ref(1)

const dialog = ref(false)
const confirmDialog = ref(false)
const itemToDelete = ref(null)
const saving = ref(false)
const form = ref({
  nome: '',
  preco_venda: ''
})

const formatCurrency = (value) => {
  return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value || 0)
}

const fetchProdutos = async () => {
  try {
    loading.value = true
    const response = await ProdutoService.getAll(page.value)
    produtos.value = response.data.data
    totalPages.value = response.data.last_page
  } catch (error) {
    console.error("Erro ao buscar produtos", error)
  } finally {
    loading.value = false
  }
}

const saveProduto = async () => {
  try {
    saving.value = true
    await ProdutoService.create(form.value)
    notifySuccess("Produto salvo com sucesso!")
    dialog.value = false
    form.value = { nome: '', preco_venda: '' }
    fetchProdutos()
  } catch (error) {
    console.error("Erro ao salvar produto", error)
    handleValidationError(error)
  } finally {
    saving.value = false
  }
}

const confirmDelete = (id) => {
  itemToDelete.value = id
  confirmDialog.value = true
}

const deleteProduto = async () => {
  if (!itemToDelete.value) return
  try {
    loading.value = true
    await ProdutoService.delete(itemToDelete.value)
    notifySuccess("Produto excluído com sucesso!")
    confirmDialog.value = false
    itemToDelete.value = null
    await fetchProdutos()
  } catch (error) {
    console.error("Erro ao excluir produto", error)
    handleValidationError(error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchProdutos()
})
</script>

<template>
  <div>
    <div class="d-flex justify-space-between align-center mb-4">
      <h2>Produtos</h2>
      <v-btn color="primary" @click="dialog = true" prepend-icon="mdi-plus">Novo Produto</v-btn>
    </div>

    <v-card>
      <v-card-text v-if="loading" class="d-flex justify-center">
        <v-progress-circular indeterminate color="primary"></v-progress-circular>
      </v-card-text>

      <v-table v-else>
        <thead>
          <tr>
            <th class="text-left">ID</th>
            <th class="text-left">Nome</th>
            <th class="text-left">Custo Médio</th>
            <th class="text-left">Preço de Venda</th>
            <th class="text-left">Estoque</th>
            <th class="text-left">Status</th>
            <th class="text-left">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="produtos.length === 0">
            <td colspan="7" class="text-center">Nenhum produto encontrado</td>
          </tr>
          <tr v-for="produto in produtos" :key="produto.id">
            <td>#{{ produto.id }}</td>
            <td>{{ produto.nome }}</td>
            <td>{{ formatCurrency(produto.custo_medio) }}</td>
            <td>{{ formatCurrency(produto.preco_venda) }}</td>
            <td>
              <v-chip :color="produto.estoque > 0 ? 'success' : 'error'" size="small">
                {{ produto.estoque }}
              </v-chip>
            </td>
            <td>
              <v-chip :color="produto.ativo ? 'success' : 'grey'" size="small">
                {{ produto.ativo ? 'Ativo' : 'Inativo' }}
              </v-chip>
            </td>
            <td>
              <v-btn icon color="error" variant="text" size="small" @click="confirmDelete(produto.id)" title="Excluir">
                <v-icon>mdi-delete</v-icon>
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
        @update:model-value="fetchProdutos"
        color="primary"
      ></v-pagination>
    </div>

    <v-dialog v-model="dialog" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="text-h5">Cadastrar Produto</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field v-model="form.nome" label="Nome do Produto" required></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field v-model="form.preco_venda" label="Preço de Venda" type="number" prefix="R$"
                  required></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue-darken-1" variant="text" @click="dialog = false">Cancelar</v-btn>
          <v-btn color="blue-darken-1" variant="text" @click="saveProduto" :loading="saving">Salvar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="confirmDialog" max-width="400px">
      <v-card>
        <v-card-title class="text-h5">Confirmar Exclusão</v-card-title>
        <v-card-text>Tem certeza que deseja excluir este produto?</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey-darken-1" variant="text" @click="confirmDialog = false">Cancelar</v-btn>
          <v-btn color="error" variant="text" @click="deleteProduto" :loading="loading">Excluir</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
