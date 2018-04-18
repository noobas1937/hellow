<template>
<div class="identitys">

 <div class="idt-1c bg-1 fs-m">
   <span class="cr-2 idt-1-1c lef">真实姓名</span>
   <span class="cr-6 idt-1-2c lef">{{uname}}</span>
 </div>

 <div class="idt-1c bg-1 fs-m">
   <span class="cr-2  idt-1-1c lef">所属部门</span>
   <span class="cr-6 idt-1-2c lef">{{site}}</span>
 </div>

 <div  class="idt-1c bg-1 fs-1">
   <span class="cr-2  idt-1-1c lef">手机号</span>
   <input class="idt-1bssc fs-m cr-6 lef" v-model="celnumber"></input>
   <button class="sep-1a2sc bg-9 cr-1 rig fs-m" @click="getIcode" :disabled="flag1">{{num}}</button>
 </div>

 <div class="idt-1c bg-1 fs-m">
  <span class="cr-2 idt-1-1c lef">验证码</span>
  <input class="idt-1bssc fs-mm cr-6 lef" placeholder='请输入验证码' v-model="code"></input>
 </div>


 <div class="idt-realizec cr-11 fs-m">
     <span class="cr-5">如姓名、站点、手机号码信息不正确，请联系站长或公司人事部门申请修改，修改完成后进入程序认证再次认证。</span>
 </div>

 <div class="idt-2c cr-1 fs-2 " @click='setMobile'>
   <div class="idt-2msc bg-9">确认提交</div>
 </div>

 
</div>

</template>




<script>
export default {
    data(){
        return{
            code:'',
            celnumber:'',
            flag1:false,
            num:'获取验证码',
            uname:'treis',
            site:'关谷站',
            idcard:'',
            eid:''
        }
    },
    created(){
       this.getUserInfo()
    },
    methods:{
         getUserInfo(){
           var idcard = localStorage.getItem('idcard'),
               mobile=localStorage.getItem('mobile'),
               site=localStorage.getItem('site'),
               name=localStorage.getItem('name'),
               eid=localStorage.getItem('eid');
           this.uname=name;
           this.site=site;
           this.celnumber=mobile;
           this.idcard=idcard;
           this.eid=eid;
         },
         getIcode(){
          this.flag1=true;
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
         var cnum=this.celnumber;
         if(ms.test(cnum)){
           this.$http.post('?action=user.post.telcode',{mobile:cnum})
           .then(res=>{
              console.log(res);

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
           var self=this;
          
           var uid=localStorage.getItem("uid");
           var eid=localStorage.getItem('eid1');
           this.$http.post('?action=wechatweb.post.userbind',
           {client:'wx',
            uid:uid,
            eid:eid,
            code:self.code,
            mobile:self.celnumber,
            })
           .then(res=>{
              console.log(res);
              if(res.data.code==3){
                  localStorage.setItem('eid',eid);
                  localStorage.setItem('mobile1',self.celnumber);
                  self.$toast({
                     title:'消息提示',
                     content: res.data.msg,
                      
                  })
                   //console.log('tianzhuanqian')
                   clearTimeout(idtimers);
                   var idtimers=setTimeout(function(){
                         self.$router.push({
                          name:'home'
                        })
                   },2000)
                  
                   // console.log('tianzhuanhou')
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
.html{
    width:100%;
    height:100%;
    background:#f3f3f6;
}
.set-phone{
    width:100%;
}
.sep-1c{
    width:100%;
    overflow: hidden;
    margin-top: 20px;
}
.sep-1ac{
    width:100%;
    height:98px;
    position: relative;
    padding:2px 0;
}
.sep-1ac input{
    height:98px;
    width:70%;
    border:none;
   
}
.sep-1a1c{
   line-height: 98px;
   
}
.sep-1bc{
    border-bottom: 1px solid #cccccc;
    
}

.sep-1a2sc{
   height:52px;
   width:150px;
   font-size:22px;
   line-height: 52px;
   border-radius: 30px;
   z-index:999;
   margin-top:19px;
   border:0;
}
.stp-msc{
    height:98px;
    margin:30px;
    line-height: 98px;
    text-align: center;
}
.stp-codesc{
   height:80px;
   line-height: 80px;
   text-align: right;
   padding-right:30px;
}
.stp-codesc image{
    width: 30px;
    height:30px;
    vertical-align: middle;
    margin-right:18px;
   
}


.identitys{
  display:flex;
  flex-direction: column;
}
.idt-1c{
  height:90px;
 
  line-height: 90px;
  padding:0 30px;
  border-bottom:2px solid #bdbdbd;
}
.idt-1ac{
  width:34px;
  height:24px;
  margin:33px 0 0px 28px;
}
.idt-1bssc{
  width:35%;
  height:88px;
  line-height: 60px;
  border:none;
 
}
.idt-2c{
  padding:0 30px;
  margin-top:60px;
}
.idt-2msc{
  width:100%;
  height:90px;
  text-align: center;
  line-height: 90px;
  border-radius:14px;
}
.idt-1-1c{
  display: block;
  width:25%;
}
.idt-1-2c{
  width:35%;  
}
.idt-realizec{
    padding:10px 30px;
}


</style>


