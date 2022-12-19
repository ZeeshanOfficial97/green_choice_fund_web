import axios from '@axios'

export default {
  namespaced: true,
  state: {},
  getters: {},
  mutations: {},
  actions: {
    fetchInquiries(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get('/inquiries', { params: queryParams })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchInquiry(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/inquiries/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchInquiryReasons(ctx) {
      return new Promise((resolve, reject) => {
        axios
          .get('/inquiries/reasons')
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
  },
}
