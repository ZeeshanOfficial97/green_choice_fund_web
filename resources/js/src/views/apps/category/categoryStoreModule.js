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
          .get('/category', { params: queryParams })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchCategory(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/category/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    addCategory(ctx, categoryData) {
      return new Promise((resolve, reject) => {
        axios
          .post('/category', { category: categoryData })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
  },
}
