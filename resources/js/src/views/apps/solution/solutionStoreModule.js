import axios from '@axios'

export default {
  namespaced: true,
  state: {},
  getters: {},
  mutations: {},
  actions: {
    fetchSolutions(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get('/solutions', { params: queryParams })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchSolution(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/solutions/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchCategoriesDDL(ctx, request) {
      return new Promise((resolve, reject) => {
        axios
          .get('/categories/ddl', request)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    addSolution(ctx, request) {
      return new Promise((resolve, reject) => {
        axios
          .post('/solutions', request, {
            'Content-Type': "multipart/form-data; charset=utf-8"
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    updateSolution(ctx, request) {
      return new Promise((resolve, reject) => {
        axios
          .post('/solutions/update', request, {
            'Content-Type': "multipart/form-data; charset=utf-8"
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    deleteSolution(ctx, params) {
      return new Promise((resolve, reject) => {
        axios
          .post('/solutions/delete', params)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

  },
}
