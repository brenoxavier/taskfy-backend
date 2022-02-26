import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    carregando: false,
    recarregar: 0
  },
  mutations: {
    setCarregando (estado, status) {
      estado.carregando = status
    },
    setRecarregar (estado) {
      estado.recarregar++
    }
  }
})

export default store
