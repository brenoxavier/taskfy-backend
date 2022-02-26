import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from './views/Login.vue'
import Ponto from './views/Ponto.vue'
import Usuarios from './views/Usuarios.vue'
import Feriados from './views/Feriados.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    redirect: {
      name: 'Login'
    }
  },
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/ponto/:idUsuario?',
    name: 'Ponto',
    component: Ponto
  },
  {
    path: '/usuarios',
    name: 'Usuarios',
    component: Usuarios
  },
  {
    path: '/feriados',
    name: 'Feriados',
    component: Feriados
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

router.beforeEach((to, from, next) => {
  if (to.name !== 'Login' && !localStorage.getItem('token')) {
    next({ name: 'Login' })
  }

  next()
})

export default router
