<template>
  <div>  
  <calender/>
  
  </div>
</template>

<script>
 import Calender from '@/components/Calender'
 import Navs from '@/components/Nav'

export default {
   data(){
       return{
           msg:'你好'
       }
   },
   components: {
     calender:Calender,
     navs:Navs
   }
}
</script>

<style>

</style>


