import Vue from 'vue'

// axios
import axios from 'axios'

const axiosIns = axios.create({
  // You can add your headers here
  // ================================
  // baseURL: 'http://127.0.0.1:8000/',
  // baseURL: 'https://green.voidsoftech.com/',
  baseURL: 'https://admin.greenchoicefund.com/',
  
  // timeout: 1000,
  // headers: {'X-Custom-Header': 'foobar'}
})

Vue.prototype.$http = axiosIns

export default axiosIns
