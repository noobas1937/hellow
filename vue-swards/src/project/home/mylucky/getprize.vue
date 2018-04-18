<template>
  <div class="raise-container bg-1">

  <div class="rsc-1 row" v-if="glswd.award">
    <img :src="glswd.award[0].img_id" mode="aspectFill" class="rsc-1a"/>
    <div class="rsc-con column">
     <span class="rsc-1b cr-1 fs-1">第{{glswd.draw.id}}期</span>
     <span class="rsc-2a fs-2 cr-2">{{glswd.award[0].name}}</span>
    </div>
  </div>

 

   
  <div class="rsc-3www column" v-if="glswd.draw">
     <span class="rsc-3a fs-mm cr-2">{{glswd.draw.end_date}}已开奖</span>
     <span class="cr-2 fs-mm rsc-3aaw">总计夺宝份额</span>
     <wv-circle :line-width="6" stroke-color="#f39838"  :value="glswd.apply_people/glswd.with_people*100" :diameter="60" style="margin:0 auto;">{{ parseInt(glswd.apply_people/glswd.with_people*100) }}%</wv-circle>
   
     <span class="rsc-3c fs-3 cr-5">恭喜您中奖了</span>
     <span class="rsc-3d fs-mm cr-6" v-if="glswd.draw.status==1">获得奖品:{{glswd.draw.title}}</span>
     <span class="rsc-3d fs-mm cr-6" v-else-if="glswd.draw.status==2">获得奖金:{{glswd.draw.przecredit}}({{glswd.draw.originalcost}}*{{parseInt(glswd.apply_people/glswd.with_people*100)}}%)</span>
     <span class="rsc-3d fs-mm cr-6" v-show="false">中奖者：{{glswd.lucker.lucky_name}}</span>
     <span class="rsc-3ew fs-mm cr-6">中奖幸运号：{{glswd.lucker.lucky_number}}</span>
     <span class="rsc-3fw fs-2 cr-1" @click='goHome'>继续抽奖</span>
  </div>
    

  

 <div  class="rsc-5s" @click="goMyluckyNumber" v-show="true">
    <div class="bg-7 rsc-5asw">
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


<style scoped>
  html{background:#f5f5f5; }
</style>

<style>
 
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
.rsc-3www{
    width:710px;
    height:500px;
    background: url(data:image/png;base64,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X/6KJp57XtHH0/TqC1Q3a45OvvC846+Oq57UpCU33qSz3/PHRe+jprlFU664Sp0vbdLA8WOOxzP7Hb+jc+76QtHfjk+l05r+hus0cOK4undsdzyeSa9aoRX33e/5W56jJl90iaonN6tj4wbH9xjWtEzRslvvcP1T0oXqZs1W8yWrPF+Dc9793uE3uiLXsrBegy2XXS6rukadL21y/FVltTNm6ty7v+j51eJR9fPme74GrdpazX3f9Vp8401FX4PpCfWads216tmzW70v73fcPuXKq3Tel7+imhJxFsY1pWHREk0891U6+fxzyti+pzVdX6/5f/JnWvDRjxW/pkycqGmvv0Zd27ao79Ah88ZUStOvuVbnfenviv6CL5VKaeqa1yvT26eurZud15T5C3Te3/x9yWvKxHNepfr5C4Yfj+37UasmTdKiv/xk0V8kS8O/azD1yqvU+dKL6j921NxpWZr51rfr3M/dWzQ2U5al6W+4TkPtberattXxGpx4zrla8bf/UPKruZMvulg1U6er44UNjr9+sXpys5Z++paSv+CrnT5DLasuV8fGDRpsazV3ptOa8653a/ntd8sq8rfHpKqqNP2N16n/yGH17N7leDxNr75AK+67v2Rstlx2udL19erYtNH5Gpw+XefccU/J31EJ4309XVenGde+ST379rj+7RTNr1mlFX/31ZJfRGq5/AqlUtbwNcX21/35fl8f5zXllW99U0NdnYmPYklKrV21MuBiDxUexdLwbxdd9H//n/ddZ7Nqf/YZdW55SdneXtXNmqOWy1+rmilTA32c3v371PrUkxo4flzVTU1quuAi1z99W8xgW5tOrHtMfQdekVVTq8bl56j54tcE+sp/dnBQ7c8+o64tm5UdHFTdnDmacvkVxWPWRdf2bWp/7jcabGtTzZQpmnzxazx/W8/LwInjan3icfUdOiBrwgRNPOdVmnzRJb6/71CSsgP9anvmaXVt26JcJqsJZ8/VlMuv8PzKnZfOzS/q5IbnNNjRoZopU9Wy+rWBvg9MGv5TyK3rHlff4UOqqm/QxPPOV9PKCwI9nkxvr1qffGL4DURS/cKFalm12vf3tUmScjl1bNqokxs3aKirU7XTZ6pl9eUl48+u7+ABta5fp/4jR4b/BorzV2rSivMD3cdQd5da1z2hnr17lLJSaliyVC2Xrg709+raX4O1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)no-repeat center;
    background-size: cover;
    
    text-align: center;
    margin:0 auto;
}
.rsc-3aaw{
     margin-bottom:10px;
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
.rsc-5asw{
    margin-top:20px;
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
.rsc-3a{
    display:block;
    margin-top:18px;
}
.rsc-3c{
    margin:14px 0 12px 0;
}
.rsc-3ew{
     margin:2px 0 10px 0;
}
.rsc-3fw{
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


