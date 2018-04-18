<template>
<div class='lucky-numer-con'>
  <div class="lucknum column" v-for="(item,index) in numList" :key="index">
    <span class="lkm-1  fs-2 cr-2" v-if="item.luckenumber[0]">第{{item.luckenumber[0].lucky_draw_id}}期</span>
    <div class="lkm-2 bg-1 column">
      <div class="lkm-2a fs-1 cr-6">
        <span class="lkm-2a-l">{{item.date}}</span>
        <span class="lkm-2a-r">使用{{item.cost_points}}奋斗金</span>
      </div>
      <span  class="lkm-2b fs-1" v-for="(item,index) in item.luckenumber" :key="index">{{item.lucky_number}}</span>
   </div>
 </div>
 <nrecord :showr="rflag"></nrecord>
 <modal :modalshow="flagmodal"></modal> 
</div>
</template>




<script>
import modal from'@/components/modal'
import nrecord from '@/components/nrecord'

export default {
  data(){
       return{
          numList:'',
          rflag:false,
          flagmodal:false,
       }
   },
   components:{
        nrecord,
        modal,
    },
   created(){
      this.getPrizeInfo()
   },
   methods:{
       getPrizeInfo(){
           var did=this.$route.params.did;
           var eid=localStorage.getItem('eid');
           var self=this;
           this.$http.post('?action=lucky.get.luckynumber',{user_id:eid,draw_id:did})
           .then(res=>{
               console.log(res.data);
               if(res.data.code==3){
                 if(res.data.data.length>0){
                    self.numList=res.data.data;
                 }else{
                     self.rflag=true
                 }
                  
               }
               else if(res.data.code==1122){
                 self.flagmodal=true;
               }
               else{
                 self.$toast({
                     title:'消息提示',
                     content:res.data.msg
                 })
               }
           })
           .catch(err=>{
               
           })
       },
      
   }
}
</script>



<style>
 .lucky-numer-con{
  background:#f5f5f5;
}
.lucknum{
  
  padding:20px;
 
}
.lkm-1{
   margin:30px 0 16px 0;
   text-align: left;
}
.lkm-2{
 
  padding:20px;
}
.lkm-2a{
  line-height: 60px;
  border-bottom:1px solid #bdbdbd;
}
.lkm-2a-l{
  float:left;
}
.lkm-2a-r{
  float:right;
}
.lkm-2b{
  line-height: 50px;
}
</style>


