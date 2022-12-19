import axios from '@axios'

export default {
  namespaced: true,
  state: {},
  getters: {},
  mutations: {},
  actions: {
    fetchCategories(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get('/categories', { params: queryParams })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchCategory(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/categories/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    addCategory(ctx, request) {

      return new Promise((resolve, reject) => {
        axios
          .post('/categories', request, {
            'Content-Type': "multipart/form-data; charset=utf-8"
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    updateCategory(ctx, request) {
      return new Promise((resolve, reject) => {
        axios
          .post('/categories/update', request, {
            'Content-Type': "multipart/form-data; charset=utf-8"
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    
    deleteCategory(ctx, params) {
      return new Promise((resolve, reject) => {
        axios
          .post('/categories/delete', params)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
  },
}
