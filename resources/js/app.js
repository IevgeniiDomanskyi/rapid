/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from 'vue'
import VueCookie from 'vue-cookie'
import VueToastr from 'vue-toastr'
import Clipboard from 'v-clipboard'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import router from './routes'
import store from './store'

Vue.config.productionTip = false

Vue.use(VueCookie)
Vue.use(VueToastr)
Vue.use(Clipboard)

Vue.prototype.$colors = {
  body: '#2a2b3c',
  primary: '#0cc2aa',
  text: '#fbfbfb',
  text_inverse: 'rgba(0, 0, 0, 0.87)',
  sidebar: '#252635',
  panel: '#313347',
  badge: '#fcc100',
  green: '#38a933',
  orange: '#f08e51',
  red: '#d53e29',
  brown: '#af8656',
  blue: '#02469b',
  grey: '#7d7d7d',
  purple: '#AF60A2',
  rose: '#C793C3',
  white: '#EEEEEE',
}

new Vue({
  el: '#app',
  vuetify,
  router,
  store,
  render: h => h(App),
})
