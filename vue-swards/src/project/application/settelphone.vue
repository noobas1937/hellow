<template>
 <div class="con-num">

 <transition name="fade-old">
 <div v-show="flags1">  
 <div  class="idt-1y bg-1 fs-0">
   <span class="cr-2  idt-1-1y" v-show="false">手机号</span>
    <div class="cos-input  lef">
     <input class="idt-1by fs-0 cr-6" v-model="celnumber" placeholder='请输入手机号' disabled="true"></input>
    </div>
   <button class="sep-1a2y bg-9 cr-1 rig" v-bind:class="flag1==true?'a':''" @click="getIcode" :disabled="flag1">{{num}}</button>
 </div>
 <div class="idt-1y bg-1 fs-0">
  <span class="cr-2 idt-1-1y" v-show="false">验证码</span>
  <input class="idt-1by fs-0 cr-6 lef" v-model='code' placeholder='请输入验证码'  value=""></input>
 </div>

 <div class="idt-2y cr-1 fs-2 bg-9" @click='setMobile'>
   <span>解除绑定</span>
 </div>
</div> 
</transition>

<transition name="fade-new">
<div  v-show="flags2">
 <div  class="idt-1y bg-1 fs-0">
   <span class="cr-2  idt-1-1y" v-show="false">手机号</span>
   <div class="cos-input  lef">
    <input class="idt-1by fs-0 cr-6" v-model="celnumber" placeholder='请输入新手机号'></input>
   </div>
   <button class="sep-1a2y bg-9 cr-1 rig" v-bind:class="flag1==true?'a':''" @click="getIcode" :disabled="flag1">{{num}}</button>
 </div>
 <div class="idt-1y bg-1 fs-0">
  <span class="cr-2 idt-1-1y" v-show="false">验证码</span>
  <input class="idt-1by fs-0 cr-6 lef" v-model='code' placeholder='请输入新手机号验证码'  value=""></input>
 </div>
 
 <div class="idt-2y cr-1 fs-2 bg-9" @click='submitMobile'>
   <span>确认提交</span>
 </div>
</div>
</transition>
<span class="fs-1 bd-tit cr-5" v-show="flags1">请先解绑原手机号</span>
<span class="fs-1 bd-tit cr-5" v-show="flags2">请输入新手机号，填入验证码后绑定</span>


  <modal :modalshow="flagmodal"></modal>
</div>
</template>




<script>
import modal from'@/components/modal'

export default {
   
   data(){
       return{
            num:'获取验证码',
            flag1:false,
            celnumber:'',
            code:'',
            flagmodal:false,
            flags1:true,
            flags2:false,
           
       }
   },
    components:{
        modal,
    },
    created(){
        this.getTel();
    },
    methods:{ 
       getTel(){
          this.celnumber=this.$route.params.tel;
        },
       getIcode(){
          console.log(this.flag1);
          this.flag1=true;
          console.log(this.flag1)
          var timer;
          var i=60;
          var self=this;
          var ms=/^1[3|4|5|6|7|8|9][0-9]\d{8}$/;
          clearInterval(timer);
          var timer=setInterval(function(){
               if(i>0){
                   i--;
                    self.num=i;
               }
               else{
                   clearInterval(timer);
                   self.num='重新获取'
                   self.flag1=false;
               }
              
          },1000)
          var num=this.celnumber;
          console.log(num);
           if(ms.test(num)){
           this.$http.post('?action=user.post.telcode',{mobile:num})
           .then(res=>{
              if(res.data.code==3){
                  self.$toast({
                      title:'消息提示',
                      content:res.data.msg,
                      
                  })
           
               }
               else{
                self.$toast({
                      title:'消息提示',
                      content:res.data.msg
                  })
              }

           }).catch(err=>{
              console.log(err);
              
          })
         }else{
             self.$toast({
                 title:'消息提示',
                 content: '手机号码输入错误',
             })
         }
       },
       setMobile(){
          var code=this.code;
          console.log(code);
          var self=this;
          
           var uid=localStorage.getItem("uid");
           var eid=localStorage.getItem('eid');
           this.$http.post('?action=user.get.verifycode',
           {
            client:'wx',
            user_id:eid,
            code:code,
            mobile:self.celnumber,
            })
           .then(res=>{
              console.log(res);
              if(res.data.code==3){
                  self.flags1=false;
                  self.flags2=true;
                  self.code='';
              }else{
                  self.$toast({
                      title:'消息提示',
                      content:res.data.msg
                  })
                  
              }
           }).catch(err=>{
              console.log(err);  
          })
        
       },
       submitMobile(){
           var self=this;
           var eid=localStorage.getItem('eid');
           this.$http.post('?action=user.post.setmobile',
           {
            user_id:eid,
            code:self.code,
            mobile:self.celnumber,
            })
           .then(res=>{
              console.log(res);
              if(res.data.code==3){
                  localStorage.setItem('eid',eid);
                  localStorage.setItem('mobile1',self.celnumber);
                  self.flags1=true;
                  self.flags2=false;
                  self.$toast({
                     title:'消息提示',
                     content: res.data.msg,
                      
                  })
                 
              }else{
                  self.$toast({
                      title:'消息提示',
                      content:res.data.msg
                  })
                  
              }
           }).catch(err=>{
              console.log(err);  
          })
       }
   }
}
</script>



<style>
.fade-old-enter-active {
  transition: all .3s ease;
}
.fade-old-leave-active {
  transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.fade-old-enter, .fade-old-leave-to
/* .slide-fade-leave-active for below version 2.1.8 */ {
  transform: translateX(100px);
  opacity: 0;
}


.fade-new-enter-active {
  transition: all .3s ease .8s;
}
.fade-new-leave-active {
  transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.fade-new-enter, .fade-new-leave-to
/* .slide-fade-leave-active for below version 2.1.8 */ {
  transform: translateX(-100px);
  opacity: 0;
}

html{
  position: relative;
}
.bd-tit{
 display: block;
 padding:30px; 
}
.set-phone{
    width:100%;
}
.sep-1{
    width:100%;
    overflow: hidden;
    margin-top: 20px;
}
.sep-1a{
    width:100%;
    height:98px;
    position: relative;
    padding:2px 0;
   
}
.a{
   background:#bdbdbd;
}
.sep-1a input{
    height:98px;
    width:70%;
    border:none;
   
}
.sep-1a1{
   line-height: 98px;
   position: absolute;
   left: 30px;
   top:0;
}
.sep-1b{
   border-bottom: 1px solid #cccccc;   
}
.sep-1a2y{
   height:52px;
   width:170px;
   font-size:22px;
   line-height: 52px;
   border-radius: 30px;
   z-index:999;
   margin-top:19px;
   margin-left:20px;
   border:none;
}
.stp-ms{
    height:98px;
    margin:30px;
    line-height: 98px;
    text-align: center;
}
.stp-codes{
   height:80px;
   line-height: 80px;
   text-align: right;
   padding-right:30px;
}
.stp-codes image{
    width: 30px;
    height:30px;
    vertical-align: middle;
    margin-right:18px;
   
}


.identity{
  margin-top:300px;
}
.con-num{
  width:100%;
  background: #f5f5f5;
}
.idt-1y{
  padding:0 30px;
  height:90px;
  
 
 
  line-height: 90px;
 
  border-bottom: 1px solid #bdbdbd;
}
.idt-1ay{
  width:34px;
  height:24px;
  margin:33px 0 0px 28px;
}
.idt-1by{
  width:400px;
  height:88px;
  line-height: 60px;
  border:none;
 
}
.idt-2y{
  width:100%;
  height:90px;
 
  text-align: center;
  line-height: 90px;
  border-radius: 8px;
  position:fixed;
  left:0;
  bottom:0;
}
.idt-1-1y{
  display: blcok;
  margin-left:26px;
  width:148px;
}

.cover{
    width:100%;
    height:100%;
    position: fixed;
    left:0;
    top:0;
    z-index:9999;
}
.cover-1{
    width:100%;
    height:100%;
    background: #000;
    opacity: 0.4;
    position: fixed;
    left:0;
    top:0;
    z-index:9999;
}
.cover-2{
    z-index:9999;
    background: url('https://api.nacy.cc/wxappimg/tickets.png')no-repeat;
    background-size: cover;
    width:600px;
    height:690px;
   
    border-radius: 14px;
     display: flex;
    flex-direction: column;
    text-align: center;
    position: fixed;
    left:50%;
    top:50%;
    margin:-300px 0 0 -303px;
}

</style>


