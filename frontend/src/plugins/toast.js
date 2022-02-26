import Vue from 'vue'
import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'

Vue.use(Toast, {
  position: 'top-center',
  timeout: false,
  closeOnClick: false,
  draggablePercent: 0.35
})
