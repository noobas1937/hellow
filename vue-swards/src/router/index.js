import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'
import Application from '@/project/application/application'
import Home from '@/project/home/home'
import Rewardlist from '@/project/home/rewardlist'
import Rewarddel from '@/project/home/rewarddel'
import Myswards from '@/project/home/myswards'
import Swardshistory from '@/project/home/swardshistory'
import Getprize from '@/project/home/mylucky/getprize'
import Noprize from '@/project/home/mylucky/noprize'
import Failprize from '@/project/home/mylucky/failprize'
import Waitprize from '@/project/home/mylucky/waitprize'
import Luckynum from '@/project/home/mylucky/luckynum'
import Rule from '@/project/home/mylucky/rule'
import Activity from '@/project/activity/activity'
import Identity from '@/project/identity/identity'
import Msinfo from '@/project/identity/msinfo'
import Myintegration from '@/project/application/myintegration'
import Myreward from '@/project/application/myreward'
import Mywages from '@/project/application/mywages'
import Personinfo from '@/project/application/personinfo'
import Settelphone from '@/project/application/settelphone'
import Myconvertibility from '@/project/application/myconvertibility'
import Myproblem from '@/project/application/myproblem'
import Depositresult from '@/project/application/depositresult'
import Useresult from '@/project/application/useresult'
import Mywagesdel from '@/project/application/mywagesdel'
import Addbankcard from '@/project/application/addbankcard'
import Useradmin from '@/project/application/useradmin'
import Checkfail from '@/project/application/checkemployee/checkfail'
import Readycheck from '@/project/application/checkemployee/readycheck'
import Setresignation from '@/project/application/checkemployee/setresignation'
import Nidentity from '@/project/newidentity/nidentity'
import Uploadcard from '@/project/newidentity/uploadcard'

Vue.use(Router)

export default new Router({
  routes: [
     {
      path: '/',
      name: 'HelloWorld',
      component: HelloWorld,
      meta: { title: '踢踢奋斗' },
    },
     {
      path: '/home',
      name: 'home',
      component: Home,
      meta: { title: '奋斗夺宝' },
    },
    {
      path: '/home/rewardlist',
      name: 'rewardlist',
      component: Rewardlist,
      meta: { title: '夺宝列表' },
    },
    {
      path: '/home/rewarddel',
      name: 'rewarddel',
      component: Rewarddel,
      meta: { title: '夺宝详情' },
    },
    {
      path: '/home/rule',
      name: 'rule',
      component: Rule,
      meta: { title: '规则' },
    },
    {
      path: '/home/myswards',
      name: 'myswards',
      component: Myswards,
      meta: { title: '我的夺宝' },
    },
    {
      path: '/home/myswards/getprize',
      name: 'getprize',
      component: Getprize,
      meta: { title: '我的夺宝' },
    },
    {
      path: '/home/myswards/noprize',
      name: 'noprize',
      component: Noprize,
      meta: { title: '我的夺宝' },
    },
    {
      path: '/home/myswards/waitprize',
      name: 'waitprize',
      component: Waitprize,
      meta: { title: '我的夺宝' },
    },
    {
      path: '/home/myswards/failprize',
      name: 'failprize',
      component: Failprize,
      meta: { title: '我的夺宝' },
    },
    {
      path: '/home/myswards/luckynum',
      name: 'luckynum',
      component: Luckynum,
      meta: { title: '我的幸运夺宝号' },
    },
    {
      path: '/home/swardshistory',
      name: 'swardshistory',
      component: Swardshistory,
      meta: { title: '历史开奖' },
    },
    {
      path: '/application',
      name: 'application',
      component: Application,
      meta: { title: '我的' },
    },
    {
      path: '/application/myintegration',
      name: 'myintegration',
      component: Myintegration,
      meta: { title: '我的奋斗金' },
    },
     {
      path: '/application/myreward',
      name: 'myreward',
      component: Myreward,
      meta: { title: '我的奖励' },
    },
    {
      path: '/application/personinfo',
      name: 'personinfo',
      component: Personinfo,
      meta: { title: '我的信息'},
    },
    {
      path: '/application/mywages',
      name: 'mywages',
      component: Mywages,
      meta: { title: '我的工资' },
    },
    {
      path: '/application/myintegration/myconvertibility',
      name: 'myconvertibility',
      component: Myconvertibility,
      meta: { title: '兑换现金' },
    },
    {
      path: '/application/myproblem',
      name: 'myproblem',
      component: Myproblem,
      meta: { title: '常见问题' },
    },
    {
      path: '/application/personinfo/settelphone',
      name: 'settelphone',
      component: Settelphone,
      meta: { title: '修改手机号'},
    },
    {
      path: '/application/myintegration/depositresult',
      name: 'depositresult',
      component: Depositresult,
      meta: { title: '提现记录'},
    },
    {
      path: '/application/myintegration/useresult',
      name: 'useresult',
      component: Useresult,
      meta: { title: '使用记录'},
    },
    {
      path: '/application/mywages/mywagesdel',
      name: 'mywagesdel',
      component: Mywagesdel,
      meta: { title: '我的工资明细'},
    },
    {
      path: '/application/personinfo/addbankcard',
      name: 'addbankcard',
      component: Addbankcard,
      meta: { title: '添加银行卡'},
    },
    {
      path: '/application/useradmin/readycheck',
      name: 'readycheck',
      component: Readycheck,
      meta: { title: '员工信息'},
    },
    {
      path: '/application/useradmin/setresignation',
      name: 'setresignation',
      component: Setresignation,
      meta: { title: '员工信息'},
    },
    {
      path: '/application/useradmin/checkfail',
      name: 'checkfail',
      component: Checkfail,
      meta: { title: '审核不通过'},
    },
    {
      path: '/application/useradmin',
      name: 'useradmin',
      component: Useradmin,
      meta: { title: '员工管理'},
    },
    {
      path: '/activity',
      name: 'activity',
      component: Activity,
      meta: { title: '最新活动' },
    },
    {
      path: '/identity',
      name: 'identity',
      component: Identity,
      meta: { title: '激活员工身份' },
    },
    {
      path: '/identity/msinfo',
      name: 'msinfo',
      component: Msinfo,
      meta: { title: '确认信息' },
    },
    {
      path: '/nidentity/',
      name: 'nidentity',
      component: Nidentity,
      meta: { title: '员工认证' },
    },
    {
      path: '/nidentity/uploadcard',
      name: 'uploadcard',
      component: Uploadcard,
      meta: { title: '上传身份信息' },
    },

  ]
})
