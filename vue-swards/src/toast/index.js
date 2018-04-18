import ToastComponent from '../components/toast.vue'
import Vue from 'vue'

let Toast = {};
Toast.install = function(Vue, options = {}) {
    // extend组件构造器
    const VueToast = Vue.extend(ToastComponent)
    let toast = null
    function $toast(params) {
        return new Promise( resolve => {
            if(!toast) {
                toast = new VueToast()
                toast.$mount()
                document.querySelector(options.container || 'body').appendChild(toast.$el)
            }
            toast.show(params)
            resolve()
        })
    }
    Vue.prototype.$toast = $toast
}
if(window.Vue){
    Vue.use(Toast)
}
export default Toast