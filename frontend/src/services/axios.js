import axios from 'axios'

const http = axios.create({
  baseURL: 'http://localhost:8080/api'
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
