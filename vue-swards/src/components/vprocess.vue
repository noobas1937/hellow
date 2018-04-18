<template>
  <div class="process">
      <div class="prochild" :style="'width:'+wid+'%'"></div>
  </div>
</template>



<script>
export default {
    data(){
        return{
       
        }
    },
    props:{
        wid:Number,
    }

}
</script>



<style>
 .process{
     width:100%;
     height:12px;
     background:#bdbdbd;
     position: relative;
     border-radius:4px;
 }
 .prochild{
     height:12px;
    
     background:#f39838;
     border-radius:4px;
     position: absolute;
     left:0;
     top:0;
     transition:all 1s ease;
     z-index: 999;
 }
</style>


