<template>
<div>
 <umation/>
 <newnavbar/>
 </div>
</template>

<script>
import umation from '@/components/Userinformation'
import navbar from '@/components/Navbar'
import newnavbar from '@/components/Newnavbar.vue'

export default {
  components: {
     navbar,
     umation,
     newnavbar, 
   },
}
</script>

<style>

</style>


