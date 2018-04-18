<template>
  <div class="raise-container">

  <div class="rsc-1 bg-1 row" v-if="glswd.award[0]">
    <img :src="glswd.award[0].img_id" mode="aspectFill" class="rsc-1a"/>
    <div class="rsc-con column">
     <span class="rsc-1b cr-1 fs-1">第{{glswd.draw.id}}期</span>
     <span class="rsc-2a fs-2 cr-2">{{glswd.award[0].name}}</span>
    </div>
  </div>

  <div class="rsc-3sw column" :style="bgm.background">
     <span class="rsc-3asss fs-mm cr-2"> {{glswd.draw.end_date}}开奖</span>
     <span class="cr-2 fs-mm">总计夺宝份额</span>
     <wv-circle :line-width="6" stroke-color="#f39838"  :value="glswd.apply_people/glswd.with_people*100" :diameter="60" style="margin:0 auto;">{{ parseInt(glswd.apply_people/glswd.with_people*100) }}%</wv-circle>
     <span class="rsc-3c fs-3 cr-2">很遗憾,筹集失败</span>
     
     <span class="rsc-3e fs-mm cr-6">呼朋唤友齐开奖：记得把夺宝活动推荐给其他同事，集齐开奖总份数才能开奖哦！</span>
     <span class="rsc-3f fs-2 cr-1" @click='goHome'>继续夺宝</span>
  </div>

   
  <div class="rsc-3ss bg-7" v-show="false">
       <img src="../../../assets/img/golds.png" alt="" class="rsc-imgs">
       <span class="fs-3">很遗憾，筹集失败</span>
       <span class="cr-6 fs-1 rsc-tits">呼朋唤友齐开奖：记得把夺宝活动推荐给其他同事，集齐开奖总份数才能开奖哦！</span>
       
  </div>

  <div>
      <span class="cr-6 fs-1 rsc-tits2">已投入奋斗金将原路退还至个人账户，可在“<span class="cr-11" @click="goMyIntergration">我的奋斗金</span>”页面查看退还详情</span>
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
          bgm:{ background:"background-image:"+"url(" + require("../../../assets/img/nopri.png") + ");"}
       }
   },
   components:{
        modal,
    },
    beforeRouteLeave(to, from, next){  
            if (to.name=='myintegration'||to.name=='luckynum'||to.name=="rewardlist") {  
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
      goMyIntergration(){
          this.$router.push({
              name:'myintegration'
          })
      }
   }
}
</script>



<style>
  html{background:#fff; }
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
.rsc-3sw{
    width:710px;
    height:560px;
    background-position: center;
    background-repeat:no-repeat;
    background-size: cover;
    display: flex;
    flex-direction: column;
    text-align: center;
    margin:0 auto;
}
 .rsc-imgs{
     width:120px;
     height:120px;
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
.rsc-tits{
    display:block;
    padding:10px 40px;
}
.rsc-tits2{
    display: block;
    padding:20px 20px 40px 20px;
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
.rsc-3asss{
    margin:12px 0 30px 0;
}
.rsc-3c{
    margin:14px 0 12px 0;
}
.rsc-3e{
     margin:2px 0 44px 0;
     padding:0 11%;
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


