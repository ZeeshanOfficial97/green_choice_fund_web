import axios from '@axios'

export default {
  namespaced: true,
  state: {},
  getters: {},
  mutations: {},
  actions: {
    fetchFaqsUser(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get('/faqs/user', { params: queryParams })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchFaqs(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get('/faqs', { params: queryParams })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchFaq(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/faqs/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    
    addFaq(ctx, request) {
      return new Promise((resolve, reject) => {
        axios
          .post('/faqs', request)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    updateFaq(ctx, request) {
      return new Promise((resolve, reject) => {
        axios
          .post('/faqs/update', request)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    deleteFaq(ctx, params) {
      return new Promise((resolve, reject) => {
        axios
          .post('/faqs/delete', params)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

  },
}
