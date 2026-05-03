import api from './api'

export default {
  getAll(page = 1) {
    return api.get(`/produtos?page=${page}`)
  },
  getForSelect() {
    return api.get('/produtos/select')
  },
  getById(id) {
    return api.get(`/produtos/${id}`)
  },
  create(data) {
    return api.post('/produtos', data)
  },
  update(id, data) {
    return api.put(`/produtos/${id}`, data)
  },
  delete(id) {
    return api.delete(`/produtos/${id}`)
  }
}
