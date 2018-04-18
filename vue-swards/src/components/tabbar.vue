<template>
  <div class="t-tab bg-1 row">
      <div class="t-item column" @click="goHome">
          <img :src="tnum1==false?gh:gho" alt="" class="t-img" >
          <span class="t-name fs-1" :class="tnum1==false?'an':'bn'">奋斗夺宝</span>
      </div>
      <div class="t-item" @click="goActivity" v-show="false">
          <img :src="tnum2==false?ac:aco" alt="" class="t-img" >
          <span class="t-name" :class="tnum2==false?'an':'bn'">最新活动</span>
      </div>
      <div class="t-item column" @click="goApplication">
          <img :src="tnum3==false?ap:apo" alt="" class="t-img" >
          <span class="t-name fs-1" :class="tnum3==false?'an':'bn'">我的</span>
      </div>
  </div>
</template>


<script>
import tabbar from '../store/tabbar'

export default {
    data(){
        return{
           gh:require('../assets/img/home1.png'),
           gho:require('../assets/img/home2.png'),
           ac:require('../assets/img/act1.png'),
           aco:require('../assets/img/act2.png'),
           ap:require('../assets/img/apps1.png'),
           apo:require('../assets/img/apps2.png')
        }
    },
    computed: {
      tnum1 () {
        return tabbar.state.tnum1
      },
      tnum2 () {
        return tabbar.state.tnum2
      },
      tnum3 () {
        return tabbar.state.tnum3
      }
    },
    methods:{
      
      goHome(){
        tabbar.commit('changeHome');
        this.$router.push({
          name:'home',
         
       })
      },
      goActivity(){
         tabbar.commit('changeActivity');
         this.$router.push({
          name:'activity',
         
        })
      },
      goApplication(){
        tabbar.commit('changeApplication');
        this.$router.push({
          name:'application',
         
        })
      }
    }
}
</script>


<style>
.bn{
  color:#f39838
}
.an{
  color:#999999;
}
 .t-tab{
     width:100%;
     height:80px;

   
   /* 
     display: flex;
     display: -webkit-flex;       
     flex-direction: row;
     -webkit-flex-direction: row; 
   
     
     display: -webkit-box;
     display: box;
     
     -webkit-box-orient:horizontal;
     box-orient:horizontal; */ 
     padding:20px 0;
     
     position:fixed;
     left: 0;
     bottom:0;
     z-index:9999;
     overflow: hidden;
 }
 .t-item{
       width:50%;
/*
     display: flex;
     display: -webkit-flex;          
     flex-direction: column; 
     -webkit-flex-direction: column; 
      flex: 1;   
      -webkit-flex: 1;  

      -webkit-box-flex:1; 
       box-flex:1;  

      display: -webkit-box;
      display: box;
      
      -webkit-box-orient:vertical;
      box-orient:vertical; */ 
     
           
 }
.t-img{
    display: block;
    width:60px;
    height:44px;
    margin:0 auto;
}
.t-name{
    height:36px;
    text-align: center;
    display: block;
}
</style>

