<template>
  <div class="goodluck-list" >

  <div v-if="glswd">
  <div class="gdl bg-1 row" v-for="(item,index) in glswd"  :key="index" @click="goSwardsRn(item.award[0].lucky_draw_id)">
     <div class="gdl-1">
        <img :src="item.award[0].img_id" mode="aspectFill" class="gdl-1a"></image>
     </div>
    
     <div class="gdl-2 column">
       <span class="cr-11 fs-1">第{{item.draw.id}}期</span>
       <span class="gdl-2a fs-2 cr-2">{{item.award[0].name}}</span>
       <span class="fs-1 cr-6" v-if="item.draw.start_time-timestr<0">开奖时间：{{item.draw.end_date}}</span>
       <span class="fs-1 cr-6" v-else-if="item.draw.start_time-timestr>=0">开始时间：{{item.draw.start_date}}</span>
       <div class="gdl-2b">
         <!--<span class="fs-3 gdl-2b-1 cr-6" >距离开奖还需<span class="cr-5">{{item.with_people-item.apply_people}}</span>人次</span>-->
          <span class="fs-2 gdl-2b-2 cr-1" v-if="item.draw.start_time-timestr<0">筹集中</span>
          <span class="fs-2 gdl-2b-2s cr-1" v-else-if="item.draw.start_time-timestr>=0">即将开始</span>
       </div>
     </div>
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
           glswd:{},
           timestr:'',
           flagmodal:false,
           rflag:false,
         }
     },
     components:{
        modal,
        nrecord
    },
     created(){
      this.getRewordList();
     },
     methods:{
         getRewordList(){
            var date = new Date();
            var time3 = Date.parse(date);
            this.timestr=time3 / 1000;
            var self=this;
            var eid=localStorage.getItem('eid');
            var num=30; 
            this.$http.get('?action=lucky.get.newsttwo&limit='+num)
            .then(res=>{
               if(res.data.code==3){
                 
                
                if(res.data.data.length>=1){
                   self.glswd=res.data.data;
                }
                else{
                   self.rflag=true
                }
               }
               else if(res.data.code==1122){
                self.flagmodal=true;
             }
             else if(res.data.code==4){
               self.rflag=true
                  
             }
              })
            .catch(err=>{console.log(err)})
         },
        goSwardsRn(rid){
           console.log(rid);
           this.$router.push({
               name:'rewarddel',
               params:{
                   rid:rid
               }
           })
         }
     },
    
}

</script>




<style>

html{
 background: #dbdbdb;
}
.goodluck-list{
  width:100%;
}
.gdl{
  padding:24px 20px;
  margin-bottom: 24px;
}
.gdl-1{
   width:166px;
   height:166px;
  
}
.gdl-2{
  margin-left:44px;
}
.gdl-2a{
  width:485px;
  line-height: 50px;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  
}
.gdl-2b{
  
}
.gdl-2b-1{
  float:left;
}
.gdl-2b-2{
  float:left;
}
.gdl-1a{
  width:164px;
  height:164px;
  
}
.gdl-2b-1{
  
}
.gdl-2b-2{
  display: block;
  width:180px;
  height:52px;
  text-align: center;
  line-height: 52px;
  background: #ff0000;
  border-radius: 14px;
  margin-top: 10px;
}
.gdl-2b-2s{
  float:left;
  display: block;
  width:180px;
  height:52px;
  text-align: center;
  line-height: 52px;
  background: #bdbdbd;
  border-radius: 14px;
  margin-top: 10px;
}
.gd-b{
  pointer-events: none;
}

</style>

