import api from './api'

export default {
  getAll(page = 1) {
    return api.get(`/vendas?page=${page}`)
  },
  create(data) {
    return api.post('/vendas', data)
  },
  cancel(id) {
    return api.post(`/vendas/${id}/cancelar`)
  }
}
