import Vue from 'vue'
import axios from 'axios'
import vueAxios from 'vue-axios'
import store from './store'

const http = axios.create({
  baseURL: process.env.VUE_APP_API_URL
})

Vue.use(vueAxios, http)

http.interceptors.request.use(configuracao => {
  store.commit('setCarregando', true)
  const token = localStorage.getItem('token')

  if (token) {
    configuracao.headers = Object.assign({
      Authorization: `Bearer ${token}`
    }, configuracao.headers)
  }

  return configuracao
},
erro => {
  store.commit('setCarregando', false)
  return Promise.reject(erro)
})

http.interceptors.response.use(resposta => {
  store.commit('setCarregando', false)
  return resposta
},
erro => {
  store.commit('setCarregando', false)
  return Promise.reject(erro)
})

export default http
