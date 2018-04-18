// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store'
import toast from './toast'
import Axios from 'axios'
import qs from 'qs'
import 'lib-flexible'
import wx from 'weixin-js-sdk'
import { Circle,Cell,Input ,Group ,Picker, Button } from 'we-vue'
import 'we-vue/lib/style.css'

Vue.component(Picker.name, Picker);

Vue.component(Input.name, Input)
Vue.component(Group.name, Group)

Vue.component(Button.name, Button)
Vue.component(Circle.name, Circle)
Vue.component(Cell.name, Cell)


Vue.use(require('vue-wechat-title'))
Vue.use(toast)


//getCookie()
Axios.defaults.baseURL = '/apiv1'
//Axios.defaults.baseURL = 'http://ttfast.com/apiv1'

//Axios.defaults.baseURL = 'http://zhh.gy.com/apiv1'
Axios.defaults.baseURL = 'https://api.nacy.cc/apiv1'

Axios.defaults.headers = {'X-Requested-With':'gzh'}
Vue.prototype.$http=Axios
Vue.config.productionTip = false
/* eslint-disable no-new */
var vm=new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App/>'
})
