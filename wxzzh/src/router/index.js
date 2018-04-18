import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'
import Nav from '@/components/Nav'
import Home from '&/home/Home'
import Mall from '&/mall/Mall'
import Order from '&/order/Order'
import Application from '&/application/Application'
import Morder from '&/morder/morder'
import Adorder from '&/adorder/Adorder'
import Confirm from '&/confirm/Confirm'
import Sites from '&/sites/Sites'
import Identity from '&/identity/Identity'
import Siteewm from '&/siteEwm/Siteewm'
import Seorder from '&/seOrder/Seorder'
import Uorder from '&/uorder/Uorder'
import Refresh from '&/refresh/Refresh'
import Userinfo from '&/userinfo/Userinfo'
Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home
    },
     {
      path: '/home',
      name: 'Home',
      component: Home
    },
    {
      path: '/mall',
      name: 'Mall',
      component: Mall
    },
    {
      path: '/order',
      name: 'Order',
      component: Order
    },
    {
      path: '/application',
      name: 'Application',
      component: Application
    },{
      path: '/morder',
      name: 'Morder',
      component: Morder
    },
    {
      path: '/adorder',
      name: 'Adorder',
      component: Adorder
    },
    {
      path: '/confirm',
      name: 'Confirm',
      component:Confirm
    },
    {
      path: '/sites',
      name: 'Sites',
      component:Sites
    },
    {
      path: '/identity',
      name: 'Identity',
      component:Identity
    },
    {
      path: '/siteEwm',
      name: 'Siteewm',
      component:Siteewm
    },
    {
      path: '/seOrder',
      name: 'Seorder',
      component:Seorder
    },
     {
      path: '/Uorder',
      name: 'Uorder',
      component:Uorder
    },
    {
      path: '/Refresh',
      name: 'Refresh',
      component:Refresh
    },
    {
      path: '/Userinfo',
      name: 'Userinfo',
      component:Userinfo
    }
  ]
})
