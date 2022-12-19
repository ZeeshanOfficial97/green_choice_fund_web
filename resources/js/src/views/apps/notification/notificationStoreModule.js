import axios from '@axios'

export default {
  namespaced: true,
  state: {},
  getters: {},
  mutations: {},
  actions: {

    sendNotification(ctx, request) {

      return new Promise((resolve, reject) => {
        axios
          .post('/notifications/broadcast', request, {
            'Content-Type': "multipart/form-data; charset=utf-8"
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

  },
}
