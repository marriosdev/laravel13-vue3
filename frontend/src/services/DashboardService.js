import api from './api'

export default {
  getEstatisticas() {
    return api.get('/dashboard/estatisticas')
  }
}
