import axios from 'axios'

const http = axios.create({
  baseURL: process.env.VUE_APP_API_URL
})

http.interceptors.request.use(configuracao => {
  const token = localStorage.getItem('token')

  if (token) {
    configuracao.headers = Object.assign({
      Authorization: `Bearer ${token}`
    }, configuracao.headers)
  }

  return configuracao
},
erro => {
  return Promise.reject(erro)
})

export default http
