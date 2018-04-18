<template>
  <div class="nb-1" >
   <el-col :span="8"  mode="horizontal">
    <el-menu id="mt-2" default-active="1"  mode="horizontal" class="el-menu-vertical-demo" @open="handleOpen" @close="handleClose">
      <el-menu-item id="mt-1" index="1"><a href="#/home">首页</a></el-menu-item>
      <el-menu-item id="mt-1" index="2"><a href="#/mall">商城</a></el-menu-item>
      <el-menu-item id="mt-1" index="3"><a href="#/order">订餐</a></el-menu-item>
      <el-menu-item id="mt-1" index="4"><a href="#/application">我的</a></el-menu-item>
    </el-menu>
  </el-col>
  </div>
</template>

<script>
export default {
 methods: {
      handleOpen(key, keyPath) {
        console.log(key, keyPath);
      },
      handleClose(key, keyPath) {
        console.log(key, keyPath);
      }
    }
  }
</script>

<style>
.nb-1{
   width:100%;
   height:100px;
   position:fixed;
   left: 0;
   bottom:0;
   display: flex;
   background:#fff;
   flex-direction: row; 
  -moz-flex-direction: row; 
   border-top:solid 1px #dcdcdc; 
}
.el-col{
  width:100%;
  height:100%;
}
.el-tabs_nav-scroll{
  width:100%;
  height:100%;
}
.el-menu{
  width:100%;
  height:100%;
}
#mt-1{
    width:25%;
    height: 100%;
    line-height:100px;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
}
#mt-2{
   background:#fff;
}
</style>


