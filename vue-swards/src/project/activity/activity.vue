<template>
  <div class="ac-activity">
       <img src="../../assets/img/a1.jpg" alt="" class="ac-imgs" mode="aspectFill">
       <img src="../../assets/img/a2.jpg" alt="" class="ac-imgs" mode="aspectFill">
       <img src="../../assets/img/a3.jpg" alt="" class="ac-imgs" mode="aspectFill">
       <img src="../../assets/img/a4.jpg" alt="" class="ac-imgs" mode="aspectFill">
       <img src="../../assets/img/a5.jpg" alt="" class="ac-imgs" mode="aspectFill">
       <img src="../../assets/img/a6.jpg" alt="" class="ac-imgs" mode="aspectFill" @click="goSSward">
    <tabbar></tabbar>
  </div>
  
</template>



<script>
import tabbar from'@/components/tabbar'

export default {
    data(){
        return{
           
        }
    },
    components:{
        tabbar,
    },
    methods:{
        goSSward(){
          this.$router.push({
            name:'rewardlist'
          })
        }
    }
}
</script>



<style>
 
.ac-activity{
    width:100%;
    height:100%;
    padding-bottom:80px;
}
.nac-1{
  width:550px;
  height:60px;
  text-align: center;
  line-height: 60px;
  
  position: absolute;
  left:50%;
  top:160px;
  margin-left: -200px;
 
}
.nac-1a{
  width:400px;
  height:60px;
  background: #fdf4d2;
  border-radius: 10px; 
}
.nac-1b{
  width:150px;
  height:60px; 
}
.ac-imgs{
  width:100%;
  outline-width:0px;  
  vertical-align:top;  
}
.nac-2{
  width:400px;
  height:60px;
  text-align: center;
  line-height: 60px;
  position: absolute;
  left:50%;
  top:220px;
  margin-left: -200px;
}
.nac-3{
   width:540px;
   height:540px;
   border-radius: 50%;
   position: absolute;
   left:50%;
   top:500px;
   margin-left: -270px;
}

.cover{
    width:100%;
    height:100%;
    position: fixed;
    left:0;
    top:0;
    z-index: 9999;

}
.cover-1{
    width:100%;
    height:100%;
    background: #000;
    opacity: 0.4;
    position: fixed;
    left:0;
    top:0;
   z-index: 9999;
}
.cover-2{
    width:540px;
    height:464px;
    background: #fff;
    background: url('https://api.nacy.cc/wxappimg/acend.png')no-repeat;
    background-size:cover;
    border-radius: 14px;
    display: flex;
    flex-direction: column;
    text-align: center;
    position: fixed;
    left:50%;
    top:50%;
    margin:-232px 0 0 -270px;
    z-index: 9999;
}
.cov-2{
   display: block;
   margin-top:300px;
}
.cov-3{
  width:200px;
  height:60px;
  margin: 526px 0 0 275px;
 
}
.fas{
  pointer-events: none;
}
</style>

