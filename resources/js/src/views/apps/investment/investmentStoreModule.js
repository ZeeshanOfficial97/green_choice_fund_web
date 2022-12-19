import axios from '@axios'

export default {
  namespaced: true,
  state: {},
  getters: {},
  mutations: {},
  actions: {
    fetchInvestments(ctx, queryParams) {
      return new Promise((resolve, reject) => {

        axios
          .get('/investments', { params: queryParams })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchInvestment(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/investments/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    updateInvestmentStatus(ctx, request) {
      return new Promise((resolve, reject) => {
        axios
          .post('/investments/status/update', request)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },


  },
}
