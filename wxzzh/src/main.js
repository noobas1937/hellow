// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import ElementUI from 'element-ui'
//import VueResource from 'vue-resource'
import Axios from 'axios'
//import VueAxios from 'vue-axios'

//import apiConfig from '../config/api.config'

//Vue.use(VueAxios, Axios)
Vue.use(ElementUI)
//Vue.use(VueResource)
Vue.config.productionTip = false
Axios.defaults.baseURL = 'https://m.ggjrfw.com/'
Vue.prototype.$http=Axios

require('lib-flexible/flexible.js') 
//import 'element-ui/lib/theme-default/index.css'

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  template: '<App/>',
  components: { App }
})
