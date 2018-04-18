<template>
  <div class="hello" :height="height+'px'" :width="width+'px'">
   <img src="../assets/img/guide.jpg" alt="" class="he-imgss" :height="height+'px'" :width="width+'px'">
    <div @click="goSwards" class="he-swards bg-9 fs-2 cr-1">奋斗夺宝</div>
    <div @click="goIdentity" class="he-jihuo bg-2 fs-2 cr-1">立即激活</div>
  </div>
</template>

<script>
import screen from '../store/screen'

export default {
  name: 'HelloWorld',
  data () {
    return {
      msg: 'Welcome to Your Vue.js App',
      width:750,
      height:1334,
    }
  },
  beforeCreate(){
     
    },

  created(){
     this.setScreen();
  },
  methods:{
    setScreen(){
       this.width=document.documentElement.clientWidth;
       this.height=document.documentElement.clientHeight; 
    },
    goSwards(){
      
     this.$router.push({
        name:'home',
        params: { 
               
            }
      })
    },
    goIdentity(){
      this.$router.push({
        name:'nidentity',
        params: { 
               
            }
      })
    }
  }
}

</script>
import 

<style>



.hello{
 
  position: relative;
}
.he-imgss{
  
  position: fixed;
  left:0;
  top:0;
}
h1, h2 {
  font-weight: normal;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
.he-swards{
  width:265px;
  height:96px;
  position: fixed;
  left: 11%;
  top:75%;
  text-align: center;
  line-height: 96px;
}
.he-jihuo{
  height:96px;
  width:265px;
  position: fixed;
  right: 11%;
  top:75%;
  text-align: center;
  line-height: 96px;
}
</style>
