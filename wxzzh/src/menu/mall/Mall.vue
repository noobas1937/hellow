<template>
<div>
  <div class='myMall'>暂未开放，敬请期待！</div>
 <newnavbar/>
 </div>
</template>

<script>
import sorder from '@/components/Sorder'
import navbar from '@/components/Navbar'
import newnavbar from '@/components/Newnavbar.vue'

export default {
  data(){
    
    return{

    }
  },
  components: {
     newnavbar,
   },
}
</script>

<style>
.myMall{
  margin:200px;
  font-size:30px;
  animation:move 5s ease infinite;

}
@-webkit-keyframes move{
   from{transform:scale(1);
   color:pink;}
   to{transform:scale(2);
   color:green;}
}
</style>


