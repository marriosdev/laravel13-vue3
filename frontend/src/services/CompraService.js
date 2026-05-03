import api from './api'

export default {
  getAll(page = 1) {
    return api.get(`/compras?page=${page}`)
  },
  create(data) {
    return api.post('/compras', data)
  }
}
