<template>
  <div class="navcontainer">
      <ul  class="navUl">
          <li class="navLi" v-for="(item,index) of menulist" :key="index" :class="uzi==index?'active2'+index:'active1'+index" @click="changeBg(index)">
              <span>{{item}}</span>  
          </li>
      </ul>
  </div>
</template>

<script>
export default {
    data(){
        return{
         menulist:['首页','商城','订餐','我的'],
         uzi:'',
        }
    },
    created(){
       this.setUzi();
    },
    methods:{
        setUzi(){
         this.uzi=this.$route.params.uzi||'';
        },
        changeBg(index){
          this.uzi=index;
           if(index==0){
             this.$router.push({ name: 'Home', params: {uzi:0}})   
           }
            else if(index==1){
             this.$router.push({ name: 'Mall', params: {uzi:1}})   
           } 
           else if(index==2) {
             this.$router.push({ name: 'Order', params: {uzi:2}})
           }
           else{
             this.$router.push({ name: 'Application', params: {uzi:3}})
           }     


        }
    }
}
</script>
 
<style>
  .navcontainer{
      width:100%;
      height:100px;
      position:fixed;
      bottom:0;
      left:0;
  }
  .navUl{
      width:100%;
      height:100px;
      border-top:solid 1px #e1e1e1;
      display:flex;
      flex-direction: row;
      background: #fff;
  }
  .navLi{
      list-style:none;
      width:25%;
      height:100%;
      line-height:160px;
      margin:0;
      font-size:26px;
  }
  .active10{
      background:url('../assets/img/home1.png')no-repeat;
      background-position:50% 20%;  
  }
  .active11{
      background:url('../assets/img/mall1.png')no-repeat;
      background-position:50% 20%;  
  }
  .active12{
      background:url('../assets/img/order1.png')no-repeat;
      background-position:50% 20%;  
  }
  .active13{
      background:url('../assets/img/application1.png')no-repeat;
      background-position:50% 20%;  
  }
  .active20{
      background:url('../assets/img/home2.png')no-repeat;
      background-position:50% 20%;  
      color:#b26d00;
     
  }
  .active21{
      background:url('../assets/img/mall2.png')no-repeat;
      background-position:50% 20%;  
      color:#b26d00;
     
  }
  .active22{
      background:url('../assets/img/order2.png')no-repeat;
      background-position:50% 20%;  
      color:#b26d00;
    
  }
  .active23{
      background:url('../assets/img/application2.png')no-repeat;
      background-position:50% 20%;  
      color:#b26d00;
  }
</style>


