<template>

  <div class="raise-containers bg-8">

  <div class="rsc-1s" v-if="swdel.award">
    <img :src="swdel.award[0].img_id"  class="rsc-1as"/>
    <span class="rsc-1bs cr-1 fs-1 bg-9">第{{swdel.award[0].lucky_draw_id}}期</span>
    <div class="rsc-1cs">
    
    </div>
     <span class="rsc-2as fs-2 cr-2">{{swdel.award[0].name}}</span>
  </div>

  <div  class="rsc-2s column">
    

    <span class="rsc-2bs fs-1 cr-6" v-show="false">满所需奋斗金，即抽取1人获取奖品</span>

    <div class="rsc-2cs" v-show="false">
      <span class="rsc-2c-1s fs-0">筹集中</span>
      <span class="rsc-2c-2s cr-6 fs-1" >集满所需总份数，即抽取1个中奖幸运码</span>
    </div>

     <div class="new-swa row" v-show="false">
         <div class="new-swa-1 fs-1">奖励</div>
         <div class="new-swa-2 new-swa-2a fs-m" :class="swdel.apply_people/swdel.with_people>=0.2?'cr-11':'cr-6'">
             <span>{{swdel.p20}}</span>
             <span>奋斗金</span>
         </div>
         <div  class="new-swa-2 new-swa-2b fs-m" :class="swdel.apply_people/swdel.with_people>=0.4?'cr-11':'cr-6'">
             <span>{{swdel.p40}}</span>
             <span>奋斗金</span>
         </div>
         <div  class="new-swa-2 new-swa-2c fs-m" :class="swdel.apply_people/swdel.with_people>=0.6?'cr-11':'cr-6'">
             <span>{{swdel.p60}}</span>
             <span>奋斗金</span>
         </div>
         <div  class="new-swa-2 new-swa-2d fs-m" :class="swdel.apply_people/swdel.with_people>=0.8?'cr-11':'cr-6'">
             <span>{{swdel.p80}}</span>
             <span>奋斗金</span>
         </div>
         <div class="new-swa-1 fs-1 new-swa-2e">奖品</div>
     </div>

    <div class="pro-con">

        

      <vprocess :wid="swdel.apply_people/swdel.with_people*100"></vprocess>

      <span class="pro-1 bg-1"  :class="swdel.apply_people/swdel.with_people>=0.2?'pro-1s':'pro-2s'"></span>
      <span class="pro-2 bg-1"  :class="swdel.apply_people/swdel.with_people>=0.4?'pro-1s':'pro-2s'"></span>
      <span class="pro-3 bg-1"  :class="swdel.apply_people/swdel.with_people>=0.6?'pro-1s':'pro-2s'"></span>
      <span class="pro-4 bg-1"  :class="swdel.apply_people/swdel.with_people>=0.8?'pro-1s':'pro-2s'"></span>
      <span class="pro-5 bg-1"  :class="swdel.apply_people/swdel.with_people>=1?'pro-1s':'pro-2s'"></span>
    </div>
    
    
     <div class="new-swas row">
         <div class="new-swa-1 fs-1">进度</div>
         <div class="new-swa-2 new-swa-2a pps fs-m column" :class="swdel.apply_people/swdel.with_people>=0.2?'cr-11':'cr-6'">
             <span>20%</span>
             
         </div>
         <div  class="new-swa-2 new-swa-2b pps fs-m column" :class="swdel.apply_people/swdel.with_people>=0.4?'cr-11':'cr-6'">
             <span>40%</span>
             
         </div>
         <div  class="new-swa-2 new-swa-2c pps fs-m column" :class="swdel.apply_people/swdel.with_people>=0.6?'cr-11':'cr-6'">
             <span>60%</span>
            
         </div>
         <div  class="new-swa-2 new-swa-2d pps fs-m column" :class="swdel.apply_people/swdel.with_people>=0.8?'cr-11':'cr-6'">
             <span>80%</span>  
         </div>
          <div  class="new-swa-2 new-swa-2e pps fs-m column" :class="swdel.apply_people/swdel.with_people>=1?'cr-11':'cr-6'">
             <span>100%</span>  
         </div>
        
     </div>



    <div class="rsc-2ds" v-show="false">
      <span class="cr-6 fs-0 rsc-2d-1s">已参与<span class="cr-2">{{swdel.apply_people}}</span>份</span>
      <span class="cr-6 fs-0 rsc-2d-2s">剩余<span class="cr-5">{{swdel.with_people-swdel.apply_people}}</span>份</span>
    </div>

  </div>

  <div class="pps-tit bg-1 cr-5">
      <span>依据进度条最终数值，抽取1人拿走对应奖金或奖品</span>
  </div>

  <div  class="rsc-3s" v-show="false">
    <div  class="rsc-3as fs-m cr-2">
       <span  class="rsc-3a-1s">开奖时间：{{swdel.draw.end_date}}</span>
       
        <span  class="rsc-3a-2s">可用奋斗金{{swdel.points}}</span>
    </div>
  </div>
  <div class="db-ticket" v-show="false">
     夺宝体验劵×{{swdel.ticketnumber}}
  </div>
  <div  class="rsc-4s" >
   <div  class="rsc-4as fs-1 row" v-show="false">
        <div  class="rsc-4a-1s">我参与</div>
        <span class="cr-11">{{swdel.draw.credits}}奋斗金</span>

        <span class="cr-6" style="margin:0 10px;">×</span>

        <div class="subs fs-6" @click='sub'>-</div>
        <input class="sa-inputs" v-model='inum'></input>
        <div class="adds fs-6" @click='add'>+</div>
        <span style="margin:0 10px;">份</span>
        <div  class="cre-rate fs-m">增加份数提高中奖率</div>
  </div> 
  
   <div   class="rsc-4bs cr-1 fs-3 bg-9" @click="rnGetSward" :style="swdel.draw.start_time-timestr>0?'pointer-events:none;background:#bdbdbd;':''" v-if="swdel.draw.start_time-timestr<0">立即夺宝</div>
    
   <div   class="rsc-4bs cr-1 fs-3 bg-9" @click="rnGetSward" :style="swdel.draw.start_time-timestr>0?'pointer-events:none;background:#bdbdbd;':''" v-else-if="swdel.draw.start_time-timestr>=0">即将开始</div>
  </div>
   
   <div  class="rsc-5saa bg-1"  v-show="true" >
    <div class=" rsc-5assss" @click="goSwardRule">
       <span class="rsc-2c-1s fs-m">筹集中</span>
       <span  class="rsc-3a-1s">开奖时间：{{swdel.draw.end_date}}</span>
       <span class="rsc-5a-31s fs-1 cr-11 rig" >开奖规则</span>
    </div>
  </div>

  <div  class="rsc-5saa bg-1" @click="goMyluckyNumber" v-show="true">
    <div class=" rsc-5assss">
       <span class="cr-6 rsc-5a-1s fs-1 lef">您拥有幸运夺宝号<span class="cr-11">×{{swdel.luckynumbers?swdel.luckynumbers:0}}</span></span>
       
       <span class="rsc-5a-31s fs-1 cr-11 rig" >查看</span>
    </div>
    
  </div>

  <div class="coversb" v-show="false">
      <div class="cover-1sb"></div>
      <div class="cover-2sb column">
         <span class="cover-2asb cr-2 fs-3">购买3份</span>
         <span class="cover-2bsb cr-6 fs-2">{{swdel.draw.title}}</span>
         <span class="cover-linesb"></span>
         <span class="cover-2csb fs-0">使用后您还剩余{{swdel.points-swdel.draw.credits*inum>0?swdel.points-swdel.draw.credits*inum:0}}奋斗金</span>
         <span class="cover-2dsb cr-1 fs-2 bg-9" @click="onSureUse">确定使用</span>
         <span class="cover-2dssb cr-1 fs-2 bg-2" @click="thinkMore">×</span>
      </div>
  </div>
  
  <transition name="slide-fade">
  <div class="coversb-1" v-show="flags">
    <div class="cover-nj-1"></div>
      <div class="cover-nj-2">
        <div class="cover-nj-head">
         <span class="cover-2asb-1 cr-2 fs-3">购买{{inum}}份</span>

         <div class="prenums cr-1 fs-m ">增加份数提高中奖率</div>

         <div class="cover-mynum row">
            <div class="cover-mynum-sub" @click="sub">
                <img src="../../assets/img/sub.png" alt="" class="cover-mynum-img">
            </div>
            <div class="cover-mynum-num">
                <input type="number" v-model="inum" class="cover-mynum-input" v-on:input="myInput">
            </div>
            <div class="cover-mynum-add" @click="add">
                <img src="../../assets/img/add.png" alt="" class="cover-mynum-img">
            </div>

         </div>

         <span class="cover-mynum-fd cr-5 fs-2">需支付{{cost}}奋斗金</span>
       </div>
       
      <div class="cover-ticket" v-show="swdel.ticketnumber>0">
        <span class="cover-ticket-tit fs-1">您有{{swdel.ticketnumber}}张优惠券可使用</span>

        <div class="cover-ticket-row row">
          <div  class="cover-ticket-con  bg-1 row" v-for="(item,index) in mid" :key="index" @click="getTicketInfo(index)">
              <div  class="cover-ticket-cons column">
                  <span class="cover-ticket-con1 cr-1">夺宝体验劵</span>
                  <span class="cover-ticket-con2 cr-2">{{tictit}}</span>
              </div>
              <div  class="cover-ticket-isActive" >
                 
                  
                   <img src="../../assets/img/gou.png" alt="" class="cover-ticket-isActive-img" v-show="mid[index]">

              </div>
          </div>

        </div>
      </div>

     <div class="cover-nj-foot">
         <span class="cover-2dsb cr-1 fs-2 bg-9" @click="onSureUse">确定支付{{cost}}奋斗金</span>
         <span class="cover-2csb-1 fs-0 cr-6">使用后您还剩余{{left}}奋斗金</span>
         <img src="../../assets/img/cha.png" alt=""  class="cover-2dssb" @click="thinkMore">
     </div>

   </div>
  </div>
 </transition> 
  <modal :modalshow="flagmodal"></modal>
  
</div> 
</template>





<script>
import vprocess from '@/components/vprocess'
import modal from'@/components/modal'



export default {
    data(){
        return{
           swdel:{},
           nids:'',
           timestr:'',
           inum:1,
           flags:false,
           mid:[],
           tictit:'可抵用1奋斗金',
           cost:"",
           left:"",
           flagmodal:false,
           
        }
    },
    components:{
      modal,
      vprocess, 
     
    },
    beforeRouteLeave(to, from, next){  
            if (to.name=='rule'||to.name=='luckynum') {  
                
                let iid=this.$route.params.rid?this.$route.params.rid:localStorage.getItem('iid'); 
                localStorage.setItem('iid', iid)  
            }else{  
                localStorage.removeItem('iid')  
            }  
            next()  
        },  
    created(){
        this.getSwardsDetail();

    },
    methods:{
         getSwardsDetail(){
            var date = new Date();
            var time3 = Date.parse(date);
            this.timestr=time3 / 1000;
            var rid=this.$route.params.rid?this.$route.params.rid:localStorage.getItem('iid');
            var self=this;
            var eid=localStorage.getItem('eid'); 
            this.$http.post('?action=lucky.get.luckyapplyinfo',{user_id: eid,draw_id: rid,})
            .then(res=>{
               if(res.data.code==3){
                  self.swdel= res.data.data;
                  self.nids=res.data.data.draw.credits;
                  self.cost=res.data.data.draw.credits;
                  self.left=res.data.data.points-res.data.data.draw.credits;
                  var s=res.data.data.ticketnumber;
                 
                  
                  for(var i=0;i<s;i++){
                     self.mid[i]=false;  
                  }
                  
                
               }
              })
            .catch(err=>{console.log(err)})
       },
       myInput(){
             var j=0;
          
          for(var i=0;i<this.mid.length;i++){
            if(this.mid[i]==true){
                     j++;
                 }
             } 
            
            var self=this;
            var rid=this.$route.params.rid?this.$route.params.rid:localStorage.getItem('iid');
            var eid=localStorage.getItem('eid'); 
            this.$http.post('?action=lucky.get.luckyparam',{user_id: eid,draw_id: rid,number:this.inum,ticketnumber:j})
            .then(res=>{
               if(res.data.code==3){
                   self.cost=res.data.data.cost;
                   self.left=res.data.data.left;
                  
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
            .catch(err=>{console.log(err)})
       },
       sub(){
           var j=0;
          
          for(var i=0;i<this.mid.length;i++){
            if(this.mid[i]==true){
                     j++;
                 }
          } 
            if(this.inum>1){
               this.inum--;
            }
            else{
                this.inum=1
            }
            var self=this;
            var rid=this.$route.params.rid?this.$route.params.rid:localStorage.getItem('iid');
            var eid=localStorage.getItem('eid'); 
            this.$http.post('?action=lucky.get.luckyparam',{user_id: eid,draw_id: rid,number:this.inum,ticketnumber:j})
            .then(res=>{
               if(res.data.code==3){
                   self.cost=res.data.data.cost;
                   self.left=res.data.data.left;
                  
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
            .catch(err=>{console.log(err)})
       },
       add(){
           var j=0;
           
          for(var i=0;i<this.mid.length;i++){
            if(this.mid[i]==true){
                     j++;
                 }
          }
            this.inum++;
            var eid=localStorage.getItem('eid'); 
            var rid=this.$route.params.rid?this.$route.params.rid:localStorage.getItem('iid');
            var self=this;
            this.$http.post('?action=lucky.get.luckyparam',{user_id: eid,draw_id: rid,number:this.inum,ticketnumber:j})
            .then(res=>{
               if(res.data.code==3){
                   self.cost=res.data.data.cost;
                   self.left=res.data.data.left;
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
            .catch(err=>{console.log(err)})
       },
       rnGetSward(){
          //var a=this.swdel.points-this.swdel.draw.credits*this.inum;
         // if(a>=0){
           
            var inum=this.inum;
            var eid=localStorage.getItem('eid'); 
            var rid=this.$route.params.rid?this.$route.params.rid:localStorage.getItem('iid');
            var self=this;
            this.$http.post('?action=lucky.get.luckyparam',{user_id: eid,draw_id: rid,number:this.inum})
            .then(res=>{
               if(res.data.code==3){
                   self.cost=res.data.data.cost;
                   self.left=res.data.data.left;
                   self.flags=true;
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
            .catch(err=>{console.log(err)})
        //  }else{
         //    this.flags=false;
         //    this.$toast({
         //       title:'消息提示',
        //        content:'奋斗金不足'
         //    })
        //  }
          
       },
        goMyluckyNumber(){
         var did=this.$route.params.rid?this.$route.params.rid:localStorage.getItem('iid');
         this.$router.push({
            name:'luckynum',
            params:{
               did:did,
            }
            
         })
       },
       onSureUse(){
           var j=0;
          
          for(var i=0;i<this.mid.length;i++){
            if(this.mid[i]==true){
                     j++;
                 }
          }
          var self=this;
          var rid=this.$route.params.rid?this.$route.params.rid:localStorage.getItem('iid');
          var num=this.inum;
          var eid=localStorage.getItem('eid'); 
          this.$http.post('?action=lucky.get.newluckyapply',{user_id: eid,draw_id: rid,number:num,ticketnumber:j})
          .then(res=>{
             if(res.data.code==3){
                   self.flags=false;
                   self.mid=[];
                   self.$toast({
                       title:'消息提示',
                       content:'参与成功'
                   })
                   self.getSwardsDetail();
                  
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
          .catch(err=>{console.log(err)})
       },
       thinkMore(){
          this.flags=false;
       },
       getTicketInfo(id){
         var val=!this.mid[id]; 
         this.mid.splice(id,1,val)
         
          var j=0;
          for(var i=0;i<this.mid.length;i++){
                 if(this.mid[i]==true){
                     j++;
                 }
          }
         
          var self=this;
          var rid=this.$route.params.rid?this.$route.params.rid:localStorage.getItem('iid');
          var num=this.inum;
          var eid=localStorage.getItem('eid'); 
          this.$http.post('?action=lucky.get.luckyparam',{user_id: eid,draw_id: rid,number:num,ticketnumber:j})
          .then(res=>{
             if(res.data.code==3){
                 self.cost=res.data.data.cost;
                 self.left=res.data.data.left;
                 
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
          .catch(err=>{console.log(err)})
       },
       goSwardRule(){
           this.$router.push({
               name:'rule'
           })
       }
    }
}
</script>




<style>
 .slide-fade-enter-active {
  transition: all 1s ease;
}
.slide-fade-leave-active {
  transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active for below version 2.1.8 */ {
  transform: translateY(100px);
  opacity: 0;
}
.prenums{
    width:260px;
    height:40px;
    background:#ff0000;
    border-radius: 20px;
    margin-bottom:10px;
    position: relative;
    left: 50%;
    transform: translateX(-50%);
    -webkit-transform: translateX(-50%);
}
.new-swa{
   
    position: relative;
    margin-bottom: 40px;
}
.new-swas{
   
    position: relative;
    margin-top: 20px;
}
.new-swa-1{

}
.new-swa-2{
    display: flex;
    flex-direction: column;
    text-align: center;
}
.new-swa-2a{
   position: absolute;
    left:20%;
    top:0%;
    transform:translateX(-50%) ;
    -webkit-transform: translateX(-50%);
}
.new-swa-2b{
   position: absolute;
    left:40%;
    top:0%;
    transform:translateX(-50%);
    -webkit-transform: translateX(-50%);
}
.new-swa-2c{
   position: absolute;
    left:60%;
    top:0%;
    transform:translateX(-50%) ;
    -webkit-transform: translateX(-50%);
}
.new-swa-2d{
   position: absolute;
    left:80%;
    top:0%;
    transform:translateX(-50%);
    -webkit-transform: translateX(-50%);
}
.new-swa-2e{
    white-space: nowrap;
    position: absolute;
    left:100%;
    top:0%;
    transform:translateX(-50%) ;
    -webkit-transform: translateX(-50%);
    text-align: right;
}
.pps{
    padding-top:5px;
}
.pps-tit{
    padding:20px 0 20px 30px;
    border-top:1px solid #bdbdbd;
}
.pro-con{
    
    position: relative;
    
}
.pro-1{
    width:15px;
    height: 15px;
    position: absolute;
    border-radius: 50%;
    left:20%;
    top:0%;
    transform:translateX(-50%) translateY(-25%);
    -webkit-transform: translateX(-50%) translateY(-25%);
    z-index: 999;
}
.pro-1s{
  box-shadow: 0 0 10px #f39838;
  border: 4px solid #f39838;
}
.pro-2s{
  box-shadow: 0 0 10px #bdbdbd;
  border: 4px solid #bdbdbd;
}
.pro-2{
    width:15px;
    height: 15px;
    position: absolute;
    border-radius: 50%;
    left:40%;
    top:0%;
    
    transform:translateX(-50%) translateY(-25%);
    -webkit-transform: translateX(-50%) translateY(-25%);
    z-index: 999;
}
.pro-3{
    width:15px;
    height: 15px;
    position: absolute;
    border-radius: 50%;
    left:60%;
    top:0%;
    
    transform:translateX(-50%) translateY(-25%);
    -webkit-transform: translateX(-50%) translateY(-25%);
    z-index: 999;
}
.pro-4{
    width:15px;
    height: 15px;
    position: absolute;
    border-radius: 50%;
    left:80%;
    top:0%;
    
    transform:translateX(-50%) translateY(-25%);
    -webkit-transform: translateX(-50%) translateY(-25%);
    z-index: 999;
}
.pro-5{
    width:20px;
    height: 20px;
    position: absolute;
    border-radius: 50%;
    left:100%;
    top:0%;
    
    transform:translateX(-50%) translateY(-25%);
    -webkit-transform: translateX(-50%) translateY(-25%);
    z-index: 999;
}
.cover-ticket{
    padding:20px;
    height:200px;
    background:#f7f7f7;
}
.cover-ticket-tit{
    display: block;
    height: 70px;
    text-align: left;

}
.a{
    background: red;
}
.cover-ticket-isActive-img{
    width:40px;
    height:40px;
    
}
.cover-nj-head{
    height:245px;
}
.cover-nj-foot{
    height:190px;
    padding-top:40px;
}
.cover-ticket-con{
    width:340px;
    height:95px;
    
    box-shadow: 0 0  5px #000;
    margin-right:10px;
}
.cover-ticket-cons{
    width:60%;
    
    text-align: left;
    
}
.cover-ticket-con1{
    width:160px;
    height:40px;
    text-align: center;
    line-height: 40px;
    display: block;
    background:#ff3101;
    margin:10px 0 0 20px;
}
.cover-ticket-con2{
     display: block;
     margin:4px 0 0 20px;
}
.cover-ticket-row{
   
}
.cover-ticket-isActive{
    width:40px;
    height:40px;
    border-radius: 50%;
    border:2px solid #bdbdbd;
    margin:20px 0 0 60px;
}
.cover-mynum{
    width:322px;
    
    margin:0 auto;
}
.cover-mynum-sub{
   width:65px;
   height:65px;
   border:2px solid #bdbdbd; 
}
.cover-mynum-num{
   width:180px;
   height:65px;
   border:2px solid #bdbdbd; 
}
.cover-mynum-input{
   width:180px;
   height:65px;
   border:0;
   text-align: center;
}
.cover-mynum-add{
   width:65px;
   height:65px;
   border:2px solid #bdbdbd; 
}
.cover-mynum-img{
    width:30px;
    height:30px;
    margin-top:15px;
}
.cover-mynum-fd{
   margin:20px 0 60px 0;
}
.cover-2asb-1{
    display:block;
    margin:30px 0 24px 0;
}
 .raise-containers{
    width: 100%;
    height:100%;
    padding-bottom:120px;
}
.rsc-1s{
    position: relative;
    width:100%;
    height:750px;
    margin-bottom: 30px;
   
}
.coversb-1{
    width:100%;
    height:100%;
    position: fixed;
    left:0;
    top:0;
    z-index: 9999;

}
.cover-nj-1{
    width:100%;
    height:100%;
    background: #000;
    opacity: 0.2;
    position: fixed;
    left:0;
    top:0;
    z-index: 9999;
}
.cover-nj-2{
    width:100%;
    
    background: #fff;
    
    display: flex;
    flex-direction: column;
    text-align: center;
    position: fixed;
    left:0;
    bottom:0;
    z-index: 9999;
}
.cover-2csb-1{
   padding:10px 0 0px 0;
}
.rsc-1as{
  width:100%;
  height:750px;
}
.rsc-1bs{
   display: block;
   width:188px;
   height:50px;
   text-align: center;
   border-radius: 0 25px 25px 0/0 25px 25px 0;
   line-height: 50px;
   position: absolute;
   left:0;
   top:22px;
}
.rsc-1cs{
  width:100%;
  height:150px;
  background:#fff;
  opacity: 0.4;
  position: absolute;
  left:0;
  bottom:0;
}
.rsc-2s{
  padding:30px 60px 30px 30px;
  background:#ffffff;
  
 
}
.rsc-2as{
  display: block;
  width:690px;
  padding:26px 30px;
  height:98px;
  position: absolute;
  left:0;
  bottom:0;
}
.cre-rate{
  width:270px;
  height: 50px;
  background: #e72142;
  border-radius: 25px;
  line-height:50px;
  text-align: center;
  position: absolute;
  top:-60px;
  right:180px;
  color:#fff;
}
.db-ticket{
    padding:10px 30px 40px 30px;
    text-align: right;
}
.rsc-2bs{
    
    margin-bottom:26px;
}
.rsc-2cs{
    margin-bottom:14px;
}
.rsc-2c-1s{
  display: block;
  width:100px;
  height:40px;
  line-height: 40px;
  text-align: center;
  border:1px solid #ffa800;
  color:#ffa800;
  float:left;
  margin-top:30px;
  
}
.rsc-2c-2s{
    float:right;
    line-height: 40px;
}
.rsc-2d-1s{
    float:left;
}
.rsc-2d-2s{
    float:right;
}
.rsc-2ds{
    margin:22px 0 20px 0;
}
.rsc-3s{
   margin-top:20px;
   padding:0 20px;
    
}
.rsc-3as{
   line-height: 45px;
   height:45px;
   
}
.rsc-3a-1s{
    float: left;
    margin-left:12px;
}
.rsc-3a-2s{
    float: right;
}
.rsc-4s{
  
    padding:0 20px;
   
}
.rsc-4as{
    
    width:100%;
    margin-top:75px;
    padding-bottom: 25px;
    line-height: 60px;
    position: relative;
}
.rsc-4a-1s{
    margin-right:28px;
}
.rsc-4a-2s{
   width:144px;
   height:60px;
   text-align: center;
   border:1px solid #bdbdbd;
   margin-right:20px;
   position: relative;
}
.rsc-4a-2ss{
   width:144px;
   height:60px;
   text-align: center;
   position: relative;
   background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJAAAAA8CAYAAACJmDMtAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAABmJLR0QAAAAAAAD5Q7t/AAAACXBIWXMAAAsSAAALEgHS3X78AAAEwElEQVR42u3df0yUdRzA8TfHyZECQjEzMVfORGprNW2z6WYTohk2jJlr2GoNu9WsrdWaqVv/1Jx/FGD+IFDLpc625lqznKl/YBKpgRaKyv0ADhB/gHA/4DiOu3v64+oKAbmHp3bH3ef1F899v8/zfIH3nu/xB5AAYKTuEWArkAekIcTYnMBJ4MMqFpkTjNRlA2deKp2dvrQkk+S0xEgvUEQxj9NPzd5uvn2vww4sTlyIsWpN+YNP5r17P3qDLtLrE1FOb9Ax9+kUpmbokxuPOWfpgLwlr98X6XWJSWZpSSZAvg5Ik21LqGVI0QGkyJ4lNJGAhCYSkNBEAhKaSEBCEwlIaCIBCU0kIKGJBCQ0kYCEJhKQ0EQCEppIQEITCUhoIgEJTSQgoYkEJCak4YgDkIDEBFw66qBytRWQgIRKjT85qSiy4vMqgAQkVLhy0skXRVZ8g0roNQlIhMV0ysWuQited2DY6xKQGJelpo8dBZYR8YAEJMbR/Gs/2wssDPYHRh2XgMSYWs/18/kKMx6nf8w5EpAYla3eTXm+mQGH/67zJCAxQvvvbraFEQ9IQOIO1y4OsC3fTH+PL6z5EpAIuX7ZQ1muCVdXePGABCT+crPJQ6nKeEACEsAt8yCly004bwypPlcCinNd1mA89k718YAEFNdut3opXW6it8M74WtIQHGqtz0YT0/bxOMBCSgu9XYE4+luGdR8LQkoztg7hyjLNXPLoj0ekIDiivPmEGW5Jm6aPP/ZNSWgOOHq8lGWa+bGVfXxPL5yOkVbs0Yd00f6ExP/v75uH+V5JjobB1SfmznXwPoj8wBImqbjm3fah43LEyjGuXv9lOeb6WhQH88DOcl81PBo6DhRnzBijgQUwwYcfsrzTbRfcKs+d8Y8Ax/UZGOYFkykemcXB99qGzFPtrAYNeDws+05M7Y69fFkzE5iU10O90wP/gH66l1dHHq7bdS58gSKQR6Xn+3PW2g526/63IzZSWyu/yeeX77s5tD6tjHnS0AxxusOsKPAgrW2b8TY+9XzWXfo4THPTZs5hc31OaTOCG5Mp3d383WJ7a73ky0shnjdAbYXWDCfHhnP2oo5zF+WCkBno4ejn1wfNp6eNYUNtQtC8ZzZf5sDRtu495QnUIwYGgiwa5UVU7Vr1PHLx52hjws/nsWKjTNDxzp9AhtqF3DvnCQAzh/u5atXW8O6rwQUA3xehYoiK1dOOMecc+E7O1sXX2XIE/z1nFVbslhSkokhRcfm+pxQPGcP9FC5ujnseycYqVMqlYWR/hqICfJ5FSpetHLpqCOs+Q89NY2N5xaEju3XhkjPmgJAww8Odr5gUXV/eQJNYj6vQtWa5rDjAWj9rZ/PnjGFjv+O5+KP6uMBCWjSCvgU9ha38Mf3dtXnmk65+HRZ07DjHSvVxwPyU9ikFPAr7F3bwvnDvRO+hvnnPvYUt/BEYTq7Xw7/Pc+d5D3QJBPwK+x7rZWzB3sivRQguIU57/a7zyJ6KAHYv84WNfEAfTrgZO2+25FeiBiHosD+N2xE2ffqeOJCjA2Nx5yvTM3QJ896LFn+d3wUUhQ4+KaNmj3dkV7Kv9mB4gQAI3XZwBbgWSA10isTUc0FnAA2VbGo6U8GfqeS65cdGAAAAABJRU5ErkJggg==)no-repeat;
   background-size: cover;
   margin-right:20px;
   border:1px solid #6600cc;
}
.rsc-4a-2ss::after{
    content: '';
    font-size:22px;
    color:red;
    width:144px;
    height:60px;
    text-align: left;
    position: absolute;
    left:0;
    top:-60px;
    transition:all 3s ease-in; 
    display: none;
}
.my-sw-chances{
  width:166px;
  height:60px;
  position: absolute;
  left:-5px;
  top:-65px;
  text-align: left;
  background:#f5f5f5;
}
.rsc-4a-2ss:hover::after{
   display: block;
}

.rsc-4bs{
    width:100%;
    height:100px;
   
   
    text-align: center;
    line-height: 100px;
    position: fixed;
    left: 0;
    bottom: 0;
    }
.rsc-5saa{
    margin-top:38px; 
    height:100px;
    padding:0 20px;
    line-height: 100px;
}
.rsc-5as{
    
    
}
.rsc-5a-31s{
    float:right;
    
}
.rsc-5a-2s{
    margin-top:18px;
}
.rsc-5a-3s{
    margin-top:22px;
}
.coversb{
    width:100%;
    height:100%;
    position: fixed;
    left:0;
    top:0;
    z-index: 9999;

}
.cover-1sb{
    width:100%;
    height:100%;
    background: #000;
    opacity: 0.2;
    position: fixed;
    left:0;
    top:0;
     z-index: 9999;
}
.cover-2sb{
    width:606px;
    height:424px;
    background: #fff;
    border-radius: 14px;
    display: flex;
    flex-direction: column;
    text-align: center;
    position: fixed;
    left:50%;
    top:50%;
    margin:-212px 0 0 -303px;
    z-index: 9999;
}

.cover-2asb{
    font-weight: bold;
    font-size: 38px;
    margin:40px 0 34px 0;
}
.cover-2bsb{
    padding:0 40px 30px 40px;
   
}
.cover-linesb{
    width:510px;
    height:2px;
    background:#bdbdbd;  
    margin: 0 auto;
}
.cover-2csb{
   padding:30px 0 26px 0;
}
.cover-2dsb{
    display: block;
    width:520px;
    height: 80px;
    text-align: center;
    line-height: 80px;
    margin:0 auto;
    border-radius: 12px;
}
.cover-2ssb{
    width:606px;
    height:630px;
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
.cover-2s1sb{
    font-size: 38px;
    font-weight: bold;
    margin:36px 0 34px 0;
}
.cover-2s2sb{
    display: flex;
    flex-direction: column;
    padding:0 20px;
    border-radius: 12px;
}
.cover-2s2-1sb{
    line-height: 64px;
    border-bottom: 1px solid #bdbdbd;
}
.cover-2s2-1asb{
    float:left;
}
.cover-2s2-1bsb{
    float:right;
}
.cover-2s2-2sb{
    margin-top:20px;
    text-align: left;
}
.cover-2s2-3sb{
    margin-top:16px;
    margin-bottom:38px;
    text-align: left;
}
.cover-2s2-1ssb{
    line-height: 64px;
   
}
.cover-2s2-2ssb{
    margin-top:20px;
    margin-bottom: 30px;
    text-align: left;
}
.cover-2s3sb{
    display: flex;
    flex-direction: column;
    padding:0 20px;
    border-radius: 12px;
    margin-top:18px;
}
.cover-2s4sb{
    display: block;
    margin-top:50px;
    width:544px;
    height:80px;
    text-align: center;
    line-height: 80px;
    background: #f39d0a;
    border-radius: 16px;
    
}
.subs,.adds{
  width:60px;
  height:60px;
  border:1px solid #bdbdbd;
  text-align: center;
  line-height: 60px;
}
.sa-inputs{
  width:90px;
  height:58px;
  border:2px solid #bdbdbd;
  text-align: center;
  z-index: 1；
}
.cover-2dssb{
    width:50px;
    height:50px;
    position: absolute;
    right:20px;
    top:20px;
    
  
   
}
</style>


