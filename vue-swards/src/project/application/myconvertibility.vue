<template>
<div>
  <div class="conver-container">

  <div class="cvc-1 bg-1 column">
    <span class="cvc-1a fs-2 cr-2">输入兑换金额</span>
    <div class="cvc-1b">
      <span class="cvc-1b-1  fs-2">奋斗金</span>
      <input  class="cvc-1b-2 fs-4" v-model='Money'>
      <span class="cvc-1b-3 fw">=</span>
      <span class="cvc-1b-4 fs-2"><span class="fs-4">{{Money}}</span>元</span>
    </div>
    <div class="cvc-1a fs-2 cr-6">可用奋斗金{{grainfo.total}}<button class="bt-1" @click="depsitResult">提现记录</button></div>
    
  </div>
  
    <div class="toexplain fs-0 cr-6 column">
        <span>提示：</span>
        <span class="">1.奋斗金除了兑换成现金提现外，还可参与夺宝活动，抽取幸运大奖。</span>
        
       
        <span>2.<span class="changeColour">奋斗金账户余额不足时，无法充值，也无法参与夺宝活动。</span>想顺利参与活动赢取大奖，请确保账户中有足够的奋斗金，不然就只能等到下个月了哟。</span>
        <span  class="cr-6 padd-1">提现到账时间：</span>
        <span>1、每月10日0:00至14日12:00之间，申请提现的金额，将统一于15日发放至工资卡。</span>
        <span>2、每月14日12:00至下月9日24:00之间，每日可申请提现一次，金额不少于100，申请提现的金额将于1个工作日内到账。</span>
      </div>
     
  <div  class="cvc-2 cr-1 fs-3 bg-9" @click='msMyChoose'>
    <span>确认兑换并直接提现</span>
  </div>
 
</div>
<transition name="fade">
  <div class="coveryy" v-show="flags">
      <div class="cover-1yy"></div>
      <div class="cover-2yy cr-2 fs-2 column">
         <span class="c2-cyy fs-3 cr-11">确认兑换并提现</span>
         <span class="c2-lyy cr-2">将<span class="cr-5">{{Money}}奋斗金</span>兑换为{{Money}}元</span>
         <span  class="c2-lyy">并提现至您的工资卡账户:</span>
         <span  class="c2-lyy cr-6">{{grainfo.bank_name}}</span>
         <span  class="c2-lyy ">卡号：<span class="cr-6">{{grainfo.bank_card}}</span></span>
         <span  class="c2-lyy">到账时间：<span class="cr-6">{{grainfo.accounting_date}}</span></span>
        <div class="c-2byy">
           <span class="cover-2dsyy cr-1 fs-2 lef" @click='mCancelExchange'>取消</span>
           <span class="cover-2dyy cr-1 fs-2 bg-9 rig" @click='mSureExchange' :class="mflag?'':'disableBtn'">确认</span>
        </div>
        <span class="c2-ryy cr-11 fs-1">如银行卡信息有误，请联系站长或者拨打客服电话{{grainfo.service_mobile}}</span>
      </div>


  </div>
</transition>
 <modal :modalshow="flagmodal"></modal>
</div>

     
</template>

<script>
import modal from'@/components/modal'
export default {
     data(){
         return{
           Money:'',
           grainfo:{
              
           },
           snum:100,
           flags:false,
           flagmodal:false,
           mflag:true,
         }
     },
     components:{
        modal,
     },
     created(){
        this.getPoints();
     },
     methods:{
         msMyChoose(){
             if(this.Money){
               this.flags=true
             }
             else{
               this.flags=false
             }
         },
         mCancelExchange(){
             this.flags=false
         },
         mSureExchange(){
             var self=this;
             this.mflag=false;
             var eid=localStorage.getItem('eid');
             this.$http.post('?action=user.post.conversion',{user_id: eid,
          money: self.Money})
          .then(res=>{
              
            if(res.data.code==3){
                //self.$router.push({
                //   name:'myintegration'
               // })
             
               self.$toast({
                 title:'消息提示',
                 content:'提现成功',
               })
              self.getPoints();

            }else{
                 self.$toast({
                   title:'消息提示',
                   content:res.data.msg
                 })
              }
           self.mflag=true;
          })
          .catch(err=>{console.log(err)})
             this.flags=false
         },
         getPoints(){
           var self=this;
           var eid=localStorage.getItem('eid');
          this.$http.post('?action=user.get.pointdetail',{user_id:eid}).then(res=>{
              console.log(res)
              if(res.data.code==3){
                  self.grainfo=res.data.data;
              } else if(res.data.code==1122){
                 self.flagmodal=true;
             }
             else{
                  self.$toast({
                     title:'消息提示',
                     content:res.data.msg
                 })
             }
              
          }).catch(error=>{
              console.log(error)
          })
         },
            depsitResult(){
        this.$router.push({
          name:'depositresult',
          params: { 
               id:1
            }
        })
       },
     }
}
</script>


<style>
 html{
  background: #f5f5f5;
}
.fade-enter-active, .fade-leave-active {
  transition: opacity 1s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
.conver-container{
  width:100%;
}
.disableBtn{
  pointer-events: none;
}
.cvc-1{
  padding:0 30px;
  margin-top:30px;
  margin-bottom: 10px;
}
.cvc-1a{
  padding:36px 0;
}
.cvc-1b{
  border-bottom:1px solid #bdbdbd;
  padding:50px 0;
 
}
.cvc-1b-1{
  float:left;
  line-height: 60px;
  margin-right:6px;
}
.cvc-1b-2{
  float:left;
  width:240px;
  height:80px;
  margin-top:-10px;
  border:none;
}
.cvc-1b-3{
  float:left;
  line-height: 60px;
}
.cvc-1b-4{
  margin-left:6px;
  float:right;
  line-height: 60px;
}
.cvc-2{
  width:690px;
  height:88px;
  text-align: center;
  margin: 0 auto;
  line-height: 88px;
  border-radius: 12px;
}

.coveryy{
    width:100%;
    height:100%;
    position: fixed;
    left:0;
    top:0;
    z-index: 9999;

}
.cover-1yy{
    width:100%;
    height:100%;
    background: #000;
    opacity: 0.4;
    position: fixed;
    left:0;
    top:0;
    z-index: 9999;
}
.cover-2yy{
    width:606px;
    height:800px;
    background: #fff;
    border-radius: 14px;
    text-align: center;
    position: fixed;
    left:50%;
    top:50%;
    margin:-400px 0 0 -303px;
    z-index: 9999;
   
}
.cover-2ayy{
  
}
.cover-2dyy{

  display: block;
  width:200px;
  height:80px;
 
  margin-right:40px;
   border-radius: 12px;
  line-height: 80px;
  text-align: center;
  margin-top:80px;
}
.cover-2dsyy{
  display: block;
  height:80px;
  width:200px;
  background:#bdbdbd;
  margin-left:40px;
  border-radius: 12px;
  line-height: 80px;
  text-align: center;
   margin-top:80px;
}
.c-2byy{
  
}
.toexplain{
   padding:0 30px;
   display: inline-block;
  
   line-height: 45px;
   margin-bottom:40px;
}
.c2-cyy{
  margin:60px  0;
  text-align: center;
}
.c2-lyy{
  text-align: left;
  margin-left:40px;
  font-size: 34px;
  line-height: 60px;
}
.c2-ryy{
  text-align: left;
  max-width:530px;
  display: inline-block;
  margin:20px 0 0 30px;
}
.bt-1{
      width: 2.5rem;
      height: 0.8rem;
      float: right;
}
.changeColour{
  color: #f39838
}
.padd-1{
  padding-top: 20px; 
}
</style>

