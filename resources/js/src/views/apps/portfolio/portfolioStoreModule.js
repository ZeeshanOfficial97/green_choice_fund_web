import axios from '@axios'

export default {
  namespaced: true,
  state: {},
  getters: {},
  mutations: {},
  actions: {
    fetchPortfolios(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get('/portfolios', { params: queryParams })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchPortfolioFilters(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get('/portfolios/filters', { params: queryParams })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
  },
}
