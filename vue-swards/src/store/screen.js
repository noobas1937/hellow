import Vue from 'vue';
import Vuex from 'vuex';



Vue.use(Vuex);

const tabbar = new Vuex.Store({
    state: {
         width:750,
         height:1334
    },
    mutations: {
        changeScreen(state){
            state.width=document.documentElement.clientWidth;
            state.height=document.documentElement.clientHeight;
        },
        
       
    },
    actions: {

    },
    modules: {
      
    }
});

export default tabbar;
