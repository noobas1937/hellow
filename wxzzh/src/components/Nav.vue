<template>
    <div class="swiper-container">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
         <img src="../assets/img/tban.png" alt="" class="simg">    
        </div>
        <div class="swiper-slide">
          <img src="../assets/img/tban.png" alt="" class="simg"> 
        </div>
        <div class="swiper-slide">
            <img src="../assets/img/tban.png" alt="" class="simg"> 
        </div>
      </div>
      <!-- 如果需要分页器 -->
      <div class="swiper-pagination"></div>

      <!-- 如果需要导航按钮 -->
      <!--<div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>-->
    </div>
</template>

<script>
 import Swiper from '../../static/swiper.min.js'
 

 export default {
   components: { 
   },
   mounted () {
     console.log('挂载好了')
     var mySwiper = new Swiper('.swiper-container', {
       direction: 'horizontal',
       autoplay:3000,
       loop: true,
       pagination: '.swiper-pagination',
       nextButton: '.swiper-button-next',
       prevButton: '.swiper-button-prev'
     })
     console.log(mySwiper)
   }
 }
</script>

<style>
@import url("../../static/swiper.min.css");
  .swiper{
    width: 100%;
    height: 324px; }
  .swiper-container{
    width: 100%;
    height: 324px;
    background:red;
  }
  .simg{
      width:100%;
      height:100%;
  }
  .csss{
      overflow: hidden;
      background: #f5f5f5;
  }
</style>