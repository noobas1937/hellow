import Vue from 'vue';
import Vuex from 'vuex';



Vue.use(Vuex);

const tabbar = new Vuex.Store({
    state: {
        tnum1:true,
        tnum2:false,
        tnum3:false,
    },
    mutations: {
        changeHome(state){
            state.tnum1=true;
            state.tnum2=false;
            state.tnum3=false;
        },
        changeActivity(state){
            state.tnum1=false;
            state.tnum2=true;
            state.tnum3=false;
        },
        changeApplication(state){
            state.tnum1=false;
            state.tnum2=false;
            state.tnum3=true;
        }
    },
    actions: {

    },
    modules: {
      
    }
});

export default tabbar;
