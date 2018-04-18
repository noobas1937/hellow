<template>
   <div class="swadrs-list bg-1">
   <div v-for="(item,index) in glswd" :key="index">


   <div v-if="item.draw.type==-2" class="ssl-1ist">
     <div class="ssl-1 row" @click="failPrizeDetail(item.draw.id)">
      <div class="cr-2 fs-2 ssl-1a">第{{item.award[0].lucky_draw_id}}期</div>
      <div class="cr-6 fs-0 ssl-1b">开奖时间：{{item.draw.end_date}}</div>
      <div class="cr-1 ssl-1gs fs-0"  style="border:1px solid #bdbdbd;background:#fff;color:#bdbdbd;">筹集失败</div>
      <img src="../../assets/img/rig.png" mode="aspectFill"  class="ssl-1d"/>
    </div>

    <div class="ssl-2 bg-1 row">
      <img :src="item.award[0].img_id" mode="aspectFill" class="ssl-2a"/>
      <div class="ssl-2b column">
        <div class="ssl-2b1 cr-2 fs-3">{{item.award[0].name}}</div>
        <div class="ssl-2b2 cr-6 fs-0">幸运号：{{item.luckynumbers}}个</div>
        <div class="ssl-2b4 cr-6 fs-0" v-show='false'>幸运号:{{item.lucker.lucky_number}}</div>
      </div>
    </div>
   </div>

  <div v-else-if="item.draw.type!=-2">

   <div class="ssl-1ist" @click="getPrizeDetail(item.draw.id)" v-if="item.is_apply==1">

    <div class="ssl-1 row">
      <div class="cr-2 fs-2 ssl-1a">第{{item.award[0].lucky_draw_id}}期</div>
      <div class="cr-6 fs-0 ssl-1b">开奖时间：{{item.draw.end_date}}</div>
      <div class="cr-1 ssl-1c fs-0">已中奖{{item.award.lucky_draw_id}}</div>
      <img src="../../assets/img/rig.png" mode="aspectFill"  class="ssl-1d"/>
    </div>

    <div class="ssl-2 bg-1 row">
      <img :src="item.award[0].img_id" mode="aspectFill" class="ssl-2a"/>
      <div class="ssl-2b column">
        <div class="ssl-2b1 cr-2 fs-3">{{item.award[0].name}}</div>
        <div class="ssl-2b3 cr-6 fs-0">获奖者：<span class="cr-5">{{item.lucker.lucky_name}}({{item.lucker.site}})</span></div>
        <div class="ssl-2b2 cr-1 fs-0" v-if="item.draw.status==1" style="background:#ff0000; align-self:left;padding:2px 4px;">获得奖品</div>
        <div class="ssl-2b2 cr-1 fs-0" v-else-if="item.draw.status==2" style="background:#f39838; align-self:left;padding:2px 4px;">获得{{item.draw.przecredit}}奋斗金</div>
        <div class="ssl-2b4 cr-6 fs-0">幸运号：{{item.lucker.lucky_number}}</div>
      </div>
    </div>

  </div>

<div class="ssl-1ist" @click="noPrizeDetail(item.draw.id)" v-else-if="item.is_apply==2">

    <div class="ssl-1 row">
      <div class="cr-2 fs-2 ssl-1a">第{{item.award[0].lucky_draw_id}}期</div>
      <div class="cr-6 fs-0 ssl-1b">开奖时间：{{item.draw.end_date}}</div>
      <div class="cr-1 ssl-1c fs-0" style="border:1px solid #bdbdbd;background:#fff;color:#bdbdbd;">未中奖</div>
      <img src="../../assets/img/rig.png" mode="aspectFill"  class="ssl-1d"/>
    </div>

    <div class="ssl-2 row">
      <img :src="item.award[0].img_id" mode="aspectFill" class="ssl-2a"/>
      <div class="ssl-2b column">
        <div class="ssl-2b1 cr-2 fs-2">{{item.award[0].name}}</div>
        <div class="ssl-2b3 cr-6 fs-0">中奖者：{{item.lucker.lucky_name}}({{item.lucker.site}})</div>
        <div class="ssl-2b2 cr-1 fs-0" v-if="item.draw.status==1" style="background:#ff0000; align-self:left;padding:2px 4px;">获得奖品</div>
        <div class="ssl-2b2 cr-1 fs-0" v-else-if="item.draw.status==2" style="background:#f39838; align-self:left;padding:2px 4px;">获得{{item.draw.przecredit}}奋斗金</div>
        <div class="ssl-2b4 cr-6 fs-0">幸运号：{{item.lucker.lucky_number}}</div>
      </div>
    </div>

  </div>


   <div class="ssl-1ist"  @click="waitPrizeDetail(item.draw.id)" v-else-if="item.is_apply==3&&item.draw.left_with_people==0">

    <div class="ssl-1 row">
      <div class="cr-2 fs-2 ssl-1a">第{{item.award[0].lucky_draw_id}}期</div>
      <div class="cr-6 fs-0 ssl-1b">开奖时间：{{item.draw.end_date}}</div>
      <div class="cr-1 ssl-1c fs-0" style="border:1px solid #ff9900;background:#fff;color:#ff9900;">待开奖</div>
      <img src="../../assets/img/rig.png" mode="aspectFill"  class="ssl-1d"/>
    </div>

    <div class="ssl-2 row">
      <img :src="item.award[0].img_id" mode="aspectFill" class="ssl-2a"/>
      <div class="ssl-2b column" >
        <div class="ssl-2b1 cr-2 fs-3">{{item.award[0].name}}</div>
        <div class="ss1-c1 fs-2 row">距离<div class="fs-3 cr-1 all-time">{{item.draw.day}}</div>天<div class="fs-3 cr-1 all-time">{{item.draw.hour}}</div>时<div class="fs-3 cr-1 all-time">{{item.draw.minute}}</div>分</div>
        <div class="ssl-2b2 cr-6 fs-0">幸运号：{{item.luckynumbers}}个</div>
     
        <div class="ssl-2b4 cr-6 fs-0" v-show="false">幸运号：{{item.lucker.lucky_number}}</div>
      </div>
    </div>

  </div>





   <div class="ssl-1ist" @click="goRaise(item.draw.id)"  v-else-if="item.is_apply==3&&item.draw.left_with_people>0" >

    <div class="ssl-1 row">
      <div class="cr-2 fs-2 ssl-1a">第{{item.award[0].lucky_draw_id}}期</div>
      <div class="cr-6 fs-0 ssl-1b">开奖时间：{{item.draw.end_date}}</div>
      <div class="cr-1 ssl-1c fs-0"  style="border:1px solid #ff9900;background:#fff;color:#ff9900;">筹集中</div>
      <img src="../../assets/img/rig.png" mode="aspectFill"  class="ssl-1d"/>
    </div>

    <div class="ssl-2 row">
      <img :src="item.award[0].img_id" mode="aspectFill" class="ssl-2a"/>
      <div class="ssl-2b column" >
        <div class="ssl-2b1 cr-2 fs-3">{{item.award[0].name}}</div>
        
        <div class="ssl-2b2 cr-6 fs-0">使用奋斗金：{{item.costPoints}}奋斗金</div>
        
        <div class="ssl-2b4 cr-6 fs-0" v-show='false'>幸运号:{{item.lucker.lucky_number}}</div>
      </div>
    </div>

  </div>


 

 
     </div>
  </div>
  <modal :modalshow="flagmodal"></modal>
  <nrecord :showr="rflag"></nrecord>
</div>




 
</template>




<script>
import modal from'@/components/modal'
import nrecord from '@/components/nrecord'

export default {
  data(){
      return{
        glswd:{},
        flagmodal:false,
        rflag:false,
        eflag:''
      }
  },
  components:{
        modal,
        nrecord
    },
  created(){
    this. getSwardsRecord();
  },
  methods:{
    getSwardsRecord(){
        var self=this;
        this.eflag=localStorage.getItem('eid');
        var eid=localStorage.getItem('eid');
        this.$http.get('?action=lucky.get.luckyapplyrecord',{params:{user_id:eid}})
        .then(res=>{
          if(res.data.code==3){
             
                if(res.data.data.length>=1){
                   self.glswd=res.data.data;
                }
                else{
                   self.rflag=true
                }
          }else if(res.data.code==1122){
                self.flagmodal=true;
             }
             else{
                  self.$toast({
                     title:'消息提示',
                     content:res.data.msg
                 })
             }
           
        })
        .catch(err=>{})
     },
     getPrizeDetail(id){
         console.log(id)
         this.$router.push({
             name:'getprize',
             params:{
                 rid:id
             }
         })
     },
     noPrizeDetail(id){
         console.log(id)
         this.$router.push({
             name:'noprize',
             params:{
                 rid:id
             }
         })
     },
     waitPrizeDetail(id){
         console.log(id)
         this.$router.push({
             name:'waitprize',
             params:{
                 rid:id
             }
         })
     },
     failPrizeDetail(id){
         console.log(id)
         this.$router.push({
             name:'failprize',
             params:{
                 rid:id
             }
         })
     },
    goRaise(id){
       this.$router.push({
             name:'rewarddel',
             params:{
                 rid:id
             }
         })
    }
  }
}
</script>




<style>
html{
   
}
.swadrs-list{
  width:100%;
  background: #dbdbdb;
  padding-bottom:30px;
}
.ssl-1ist{
  margin-bottom:30px;
}
.ssl-1{
  background:#fff;
  
  height:96px;
  line-height: 96px;
  padding:0 20px;
  border-bottom:1px solid #dbdbdb;
}
.ssl-2{
  background:#fff;
}
.ssl-1a{
  display: block;
  width:159px;
  
}
.ssl-1b{
  display: block;
  width: 300px;
  text-align: center;
  overflow: hidden;
}
.ssl-1c{
    display: block;
    width:98px;
    height: 42px;
    line-height: 42px;
    text-align: center;
    background:#ff0000;
    margin-top:22px;
   
    margin-left:80px;
}
.ssl-1g{
    display: block;
    width:98px;
    height: 42px;
    line-height: 42px;
    text-align: center;
    background:#bdbdbd;
    margin-top:22px;
    margin-left:80px;
}
.ssl-1gs{
    display: block;
    width:128px;
    height: 42px;
    line-height: 42px;
    text-align: center;
    background:#bdbdbd;
    margin-top:22px;
    margin-left:80px;
}
.ssl-1d{ 
  width: 32px;
  height:32px;
  margin:28px 0 0 20px;
}
.ssl-2{
   
    padding:20px;
    height:240px;
}
.ssl-2a{
    width:180px;
    height:200px;
   
}
.ssl-2b{
  
    margin-left:30px;
}
.ssl-2b1{
    margin-bottom:10px;
    overflow: hidden;
   text-overflow: hidden;
   display: -webkit-box;
   -webkit-line-clamp: 2;
   -webkit-box-orient: vertical;
}
.ssl-2b2{
    margin:4px 0;
}
.ssl-2b3{
   
}
.ssl-2b4{
    margin-top:1px;
}
.all-time{
   display: block;
   background: #ff0000;
   width:70px;
   height:54px;
   text-align: center;
   line-height: 54px;
   margin:10px 5px 0 5px;
}
.ss1-c1{
   
    line-height: 81px;
   
}
.ml-name{
  display: flex;
  flex-direction: row;
}
</style>


