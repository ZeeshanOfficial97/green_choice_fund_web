import axios from '@axios'

export default {
  namespaced: true,
  state: {},
  getters: {},
  mutations: {},
  actions: {
    fetchEulas(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get('/eulas', { params: queryParams })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchEula(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/eulas/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    addEula(ctx, request) {

      return new Promise((resolve, reject) => {
        axios
          .post('/eulas', request, {
            'Content-Type': "multipart/form-data; charset=utf-8"
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },


  },
}
