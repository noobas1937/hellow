<template>
<div class="csss">
  <navs/>
  <morder/>
  <needs/>
  <foods/>

  <alertbox/>
  <newnavbar/>
  <div class='precover'></div>
  </div>
</template>
<script>
 import Navs from '@/components/Nav'
 import Morder from '@/components/Morder'
 import Needs from '@/components/Needs'
 import Foods from '@/components/Foods'
 import Navbar from '@/components/Navbar'
 
 import Alertbox from '@/components/Alertbox'
 import newnavbar from '@/components/Newnavbar.vue'

export default {
  components: {
     navs:Navs,
     morder:Morder,
     needs:Needs,
     foods:Foods,
     navbar:Navbar,
    
     alertbox:Alertbox,
      newnavbar,
   },
}
</script>

<style>
.csss{
      overflow: hidden;
      background: #f5f5f5;
      width:100%;
      height:100%;
  }
  .precover{
    height:100px;
    width:100%;
  }
</style>


