<template>
  <div class="raise-container bg-1">

  <div class="rsc-1 row" v-if="glswd.award">
    <img :src="glswd.award[0].img_id" mode="aspectFill" class="rsc-1a"/>
    <div class="rsc-con column">
     <span class="rsc-1b cr-1 fs-1">第{{glswd.draw.id}}期</span>
     <span class="rsc-2a fs-2 cr-2">{{glswd.award[0].name}}</span>
    </div>
  </div>

 

   
  <div class="rsc-3sssw column" :style="bgm.background">
     <span class="rsc-3ass fs-mm cr-2"> {{glswd.draw.end_date}}开奖</span>
     <span class="cr-2 fs-mm">总计夺宝份额</span>
     <wv-circle :line-width="6" stroke-color="#f39838"  :value="glswd.apply_people/glswd.with_people*100" :diameter="60" style="margin:0 auto;">{{ parseInt(glswd.apply_people/glswd.with_people*100) }}%</wv-circle>
     <span class="rsc-3c fs-3 cr-2">很遗憾您未中奖</span>
     <span class="rsc-3d fs-mm cr-6" v-if="glswd.draw.status==1">获得奖品:{{glswd.draw.title}}</span>
     <span class="rsc-3d fs-mm cr-6" v-else-if="glswd.draw.status==2">获得奖金:{{glswd.draw.przecredit}}({{glswd.draw.originalcost}}*{{parseInt(glswd.apply_people/glswd.with_people*100)}}%)</span>
     <span class="rsc-3d fs-mm cr-6">中奖者：{{glswd.lucker.lucky_name}}({{glswd.lucker.site}})</span>
     <span class="rsc-3e fs-mm cr-6">中奖幸运号：{{glswd.lucker.lucky_number}}</span>
     <span class="rsc-3f fs-2 cr-1" @click='goHome'>继续夺宝</span>
  </div>
    

  

  <div  class="rsc-5s" @click="goMyluckyNumber" v-show="true">
    <div class="bg-7 rsc-5as">
       <span class="cr-6 rsc-5a-1s fs-1 lef">您拥有幸运夺宝号<span class="cr-11">X{{glswd.luckynumbers}}</span></span>
       
       <span class="rsc-5a-31s fs-1 cr-11 rig" >查看</span>
    </div>
    
  </div>

 <div class="cover" v-show="false">
      <div class="cover-1"></div>
      <div class="cover-2s">
        <span class="cover-2s1 cr-1 fs-6">我的第321450期夺宝幸运码</span>
        <div class="cover-2s2 bg-1">
          <div class="cover-2s2-1 cr-6">
            <span class="cover-2s2-1a fs-3">2018-01-25 12:00</span>
            <span class="cover-2s2-1b fs-3">使用20积分</span>
          </div>
         
          <span class="cover-2s2-2 cr-2 fs-3">312467879683124678</span>
          <span class="cover-2s2-3 cr-5 fs-3">312467879683124678</span>
        </div>
        <div class="cover-2s3 bg-1">
           <div class="cover-2s2-1s cr-6">
            <span class="cover-2s2-1a fs-3">2018-01-25 12:00</span>
            <span class="cover-2s2-1b fs-3">使用20积分</span>
          </div>
          <span class="cover-2s2-2s cr-2 fs-3">312467879683124678</span>
        </div>
        <span class="cover-2s4 cr-1" >关闭</span>
      </div>
    </div>
    
  
 
  <modal :modalshow="flagmodal"></modal>   
</div>
</template>



<script>
import modal from'@/components/modal'
export default {
   data(){
       return{
          glswd:'',
          did:'',
          flagmodal:false,
          sliderValue:12,
          bgm:{ background:"background-image:"+"url(" + require("../../../assets/img/nopri.png") + ");"}
       }
   },
   components:{
        modal,
    },
   beforeRouteLeave(to, from, next){  
            if (to.name=='luckynum'||to.name=="rewardlist") {  
                let iid=this.$route.params.rid?this.$route.params.rid:localStorage.getItem('vid'); 
                localStorage.setItem('vid', iid)  
            }else{  
                localStorage.removeItem('vid')  
            }  
            next()  
        }, 
   created(){
      this.getPrizeInfo()
   },
   methods:{
       getPrizeInfo(){
           var eid=localStorage.getItem('eid');
           var did=this.$route.params.rid?this.$route.params.rid:localStorage.getItem('vid');
           this.did=did;
           var self=this;
           this.$http.post('?action=lucky.get.luckyapplyinfo',{user_id:eid,draw_id:did})
           .then(res=>{
               if(res.data.code==3){
                self.glswd=res.data.data;
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
       goHome(){
         this.$router.push({
            name:'rewardlist'
         })
       },
       goMyluckyNumber(){
         var did=this.did;
         this.$router.push({
            name:'luckynum',
            params:{
               did:did,
            }
            
         })
       },
   }
}
</script>



<style>
  html{background:#f5f5f5; }
 .raise-container{
    width: 100%;
    background: #f5f5f5;
}
.rsc-1{
    padding:30px;
    border-bottom: 1px solid #bdbdbd;
   
    margin-bottom:30px;
}
.rsc-con{
  
   margin-left:26px;
}
.rsc-1a{
  width:200px;
  height:200px;
}
.rsc-1b{
   display: block;
   width:188px;
   height:50px;
   background: #f39838;
   text-align: center;
   border-radius: 25px;
   line-height: 50px;
}
.rsc-2{
  padding: 0 20px;
  background:#fff;
  display: flex;
  flex-direction: column;
}
.rsc-2a{
    margin:30px 0 26px 0;
}
.rsc-2b{
    margin-bottom:26px;
}
.rsc-2c{
    margin-bottom:14px;
}
.rsc-2c-1{
  display: block;
  width:100px;
  height:40px;
  line-height: 40px;
  text-align: center;
  border:1px solid #ffa800;
  color:#ffa800;
  float:left;
}
.rsc-2c-2{
    float:right;
    line-height: 40px;
}
.rsc-2d-1{
    float:left;
}
.rsc-2d-2{
    float:right;
}
.rsc-2d{
    margin:22px 0 20px 0;
}
.rsc-3sssw{
    width:710px;
    height:560px;
    background-position: center;
    background-repeat:no-repeat;
    background-size: cover;
    text-align: center;
    margin:0 auto;
}
.rsc-5{
    margin-top:300px;
    padding:0 20px;
    margin-bottom: 100px;
   
}
.rsc-5a{
    
    padding:20px;
    display: flex;
    flex-direction: column;
}
.rsc-5s{
    margin-top:38px;
    padding:0 20px;
    margin-bottom: 100px;
}
.rsc-5as{
    padding:20px;
    height:80px;
    line-height: 80px;
}
.rsc-5a-31{
    float:right;
    color:#6600cc;
}
.rsc-5a-2{
    margin-top:18px;
}
.rsc-5a-3{
    margin-top:22px;
}
.cover{
    width:100%;
    height:100%;
    position: fixed;
    left:0;
    top:0;

}
.cover-1{
    width:100%;
    height:100%;
    background: #000;
    opacity: 0.4;
    position: fixed;
    left:0;
    top:0;
}
.cover-2{
    width:606px;
    height:444px;
    background: #fff;
    border-radius: 14px;
     display: flex;
    flex-direction: column;
    text-align: center;
    position: fixed;
    left:50%;
    top:50%;
    margin:-222px 0 0 -303px;
}

.cover-2a{
    font-weight: bold;
    font-size: 38px;
    margin:40px 0 34px 0;
}
.cover-2b{
    padding:0 40px 30px 40px;
   
}
.cover-line{
    width:510px;
    height:2px;
    background:#bdbdbd;  
    margin: 0 auto;
}
.cover-2c{
   padding:30px 0 26px 0;
}
.cover-2d{
    display: block;
    width:520px;
    height: 80px;
    text-align: center;
    line-height: 80px;
    background: #6600cc;
    margin:0 auto;
    border-radius: 12px;
}
.cover-2s{
    width:606px;
    height:630px;
    background: #6600cc;
    border-radius: 14px;
    padding:30px;
    display: flex;
    flex-direction: column;
    text-align: center;
    position: fixed;
    left:50%;
    top:50%;
    margin:-315px 0 0 -303px;
}
.cover-2s1{
    font-size: 38px;
    font-weight: bold;
    margin:36px 0 34px 0;
}
.cover-2s2{
    display: flex;
    flex-direction: column;
    padding:0 20px 20px 20px;
    border-radius: 12px;
}
.cover-2s2-1{
    line-height: 64px;
    border-bottom: 1px solid #bdbdbd;
}
.cover-2s2-1a{
    float:left;
}
.cover-2s2-1b{
    float:right;
}
.cover-2s2-2{
    margin-top:20px;
    text-align: left;
}
.cover-2s2-3{
    margin-top:16px;
    margin-bottom:38px;
    text-align: left;
}
.cover-2s2-1s{
    line-height: 64px;
   
}
.cover-2s2-2s{
    margin-top:20px;
    margin-bottom: 30px;
    text-align: left;
}
.cover-2s3{
    display: flex;
    flex-direction: column;
    padding:0 20px 20px 20px;
    border-radius: 12px;
    margin-top:18px;
}
.cover-2s4{
    display: block;
    margin-top:50px;
    width:544px;
    height:80px;
    text-align: center;
    line-height: 80px;
    background: #f39d0a;
    border-radius: 16px;  
}
.rsc-3b{
  width:128px;
  height:128px;
  border-radius: 50%;
  margin:0 auto;
}
.rsc-3ass{
    margin:14px 0 30px 0;
}
.rsc-3c{
    width:50%;
    position: relative;
    left:50%;
    top:0;
    transform: translateX(-50%);
    border-top:1px solid #bdbdbd;
}
.rsc-3e{
     margin:2px 0 44px 0;
}
.rsc-3f{
    display: block;
    width:390px;
    height:84px;
    background: #f39d0a;
    line-height: 84px;
    text-align: center;
    border-radius: 12px;
    margin:0 auto;
}

</style>


