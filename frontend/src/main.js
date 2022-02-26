import Vue from 'vue'
import App from './App.vue'
import store from './plugins/store'
import router from './router'
import vuetify from './plugins/vuetify'

import '@/plugins/http'
import '@/plugins/toast'

Vue.config.productionTip = false

new Vue({
  router,
  vuetify,
  store,
  render: h => h(App)
}).$mount('#app')
