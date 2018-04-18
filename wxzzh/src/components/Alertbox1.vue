<template>
  <div class="zcontainer">
   <div class="zt-1"></div>
   <div class="zt-2a">
      <span class="zt-3a">身份认证，享受特权</span>
      <span class="zt-4a">去认证</span>
      <div class='zt-rm'>X</div>
   </div>
  </div>
</template>

<script>
export default {
  
}
</script>

<style>
 .zcontainer{
  width:100%;
  height:100%;
 
}
.zt-1{
  width:100%;
  height:100%;
  background:#000;
  opacity:0.4;
  z-index:666;
  position:fixed;
  left:0;
  top:0;
}
.zt-2a{
  width:548px;
  height:292px;
  background:#fff;
  z-index:888;
  position:fixed;
  left:50%;
  top:50%;
  margin:-146px 0 0 -274px;
  border-radius: 30px;
  display: flex;
  flex-direction: column;
}
.zt-rm{
  width:50px;
  height:50px;
  border-radius: 50%;
  position: absolute;
  right:-25px;
  top:-25px;
  border:3px solid #ff9c00;
  text-align: center;
  line-height: 50px;
  background:#fff;
  color:#ff9c00;
}
.zt-3a{
  font-size:36px;
  margin:40px 0 40px 0;
}
.zt-4a{
  display: block;
  width:60%;
  height: 80px;
  background:#ff9c00;
  font-size:30px;
  color:#fff;
  line-height:80px;
  margin:0 auto;
  border-radius:40px;
}
</style>


