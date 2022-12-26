import axios from '@axios'

export default {
  namespaced: true,
  state: {},
  getters: {},
  mutations: {},
  actions: {
    fetchInfographics(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get('/infographics', { params: queryParams })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchInfographic(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/infographics/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    addInfographic(ctx, request) {

      return new Promise((resolve, reject) => {
        axios
          .post('/infographics', request, {
            'Content-Type': "multipart/form-data; charset=utf-8"
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },


  },
}
