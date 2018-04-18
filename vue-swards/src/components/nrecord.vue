<template>
  <div class="no-record" v-show="showr">
      <div class="no-record-content column">
          <img src="../assets/img/norec.png" alt="" class="no-record-img">
          <span class="no-record-title cr-6 fs-2">暂无记录</span>
      </div>
  </div>
</template>




<script>
export default {
     props:{
         showr:Boolean
     }
}
</script>


<style>
.no-record{
   width:100%;
   height:1000px;
   position:fixed;
   left:0;
   top:0;

}
.no-record-content{
   width:320px;
   height:200px;
   position:fixed;
   left:50%;
   top:50%;
   margin:-180px 0 0 -160px;
 
}
.no-record-img{
   width:290px;
   height:185px;
   margin:0 auto;
}
.no-record-title{
   text-align: center;
   line-height:100px;
}
</style>


