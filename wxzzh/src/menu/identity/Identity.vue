<template>
  <div class="idy">
      <ul class="iy-1">
          <li class="iy-2">
              <span class="iy-7">真实姓名</span>
              <input type="text" v-model="name" value='name' placeholder="请输入真实姓名"   v-on:blur="getName()">
          </li>
          <li class="iy-3">
              <span class="iy-7">身份证号</span>
              <input type="text"  v-model="idcard" value='idcard'  placeholder="请输入身份证号" v-on:blur="getIdcard()">
          </li>
          <li class="iy-4" v-show="false">
              <span class="iy-7">手机号</span>
              <input type="text"  v-model="telnumber"  placeholder="请输入手机号">
              <button class="iy-8" @click="getYnum" v-bind:disabled="disabled" :class="active?'a':'b'">{{ydata}}</button>
          </li>
          <li class="iy-5" v-show="false">
              <span class="iy-7">验证码</span>
              <input type="text"  v-model="yznum"  placeholder="请输入验证码">
          </li>
          <li class="iy-6" v-show="false">
              <span class="iy-7">所在站点</span>
              <input type="text"  placeholder="请输入验证码">
          </li>
      </ul>
      <div class="iy-9" @click='msInfo'>确认</div> 

  <div class="zcontainer" v-show="active">
   <div class="zt-1"></div>
   <div class="zt-2s">
      <span class="zt-3s">{{statusinfos1}}</span>
      <span class="zt-5">{{statusinfos2}}</span>
      <span class="zt-4s" v-if='statusaction==1' @click='getFood()'>{{statustext}}</span>
      <span class="zt-4s" v-if='statusaction==0' @click='getReset()'>{{statustext}}</span>
      <div class='zt-rm' @click="changeActive()">X</div>
   </div>
  </div> 
  </div>
</template>


<script>
export default {
    data(){
        return{
            name:'',
            idcard:'',
            yznum:'',
            ydata:'获取验证码',
            disabled:false,
            active:false,
            statusinfos1:'',
            statusinfos2:'',
            statustext:'',
            statusaction:'',
        }
    },
    created(){
       
    },
    methods:{
     getYnum(){
         var i=60;
         var self=this;
    var yinterval=setInterval(function(){
        if(i>0){
            i--;
            self.ydata=i+'秒后重新获取';
            self.disabled=true;
            //self.active=true;
        }
        else if(i==0){
           self.ydata='获取验证码';
           self.disabled=false;
           //self.active=false;
           clearInterval(yinterval) 
        }
    },1000)  
   },
   getName(){
          console.log(this.name)
          if(!this.name){
             this.name='请输入姓名'
          }
      },
   getIdcard(){
     console.log(this.idcard)
       var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;  
          if(reg.test(this.idcard) === false)  
            {  
              this.idcard='输入错误';  
               return  false;  
        }  
          else{
              this.idcard=this.idcard;
          }
         
         
      },
   changeActive(){
      this.active=false;
   },
   msInfo(){
       var uid=localStorage.getItem('uid');
       this.$http.post('api/auth/authentication',{idcard:this.idcard,userid:uid})
       .then(response=>{
           console.log(response.data);
           var uinfo=response.data.data;
           console.log(uinfo)
           if(response.data.status=='success'){
            localStorage.setItem('identity','已认证');
            localStorage.setItem('uname',uinfo.name);
            localStorage.setItem('usiteid',uinfo.site_id);
            localStorage.setItem('umaster',uinfo.stationmaster);
            localStorage.setItem('uId',uinfo.Id);
            localStorage.setItem('bsiteid',uinfo.blesite_id);
            localStorage.setItem('sitename',uinfo.site_name);
            this.active=true;
            this.statusinfos1='恭喜您认证成功',
            this.statusinfos2='赶快去享受健康美食吧',
            this.statustext='我知道了',
            this.statusaction=1;
           }
           else{
            this.active=true;
            this.statusinfos1='很抱歉您未通过验证',
            this.statusinfos2='请及时与人事部反馈',
            this.statustext='我知道了',
            this.statusaction=0;
            localStorage.setItem('identity','未认证')
           }
        })
       .catch(error=>{console.log(error)})
   },
   getReset(){
       this.active=false;
   },
   getFood(){
       this.active=false;
       this.$router.push({ name: 'Home', params: {  }})
   }

}

}
</script>

<style>
.idy{
    text-align: left;
    overflow: hidden;
    font-size:26px;
}
.idy input{
    width:55%;
    height:100%;
    border:none;
}
.iy-1 li{
    width:100%;
    height:90px;
     border-bottom:1px solid #e1e1e1;
     overflow: hidden;
     line-height: 90px;
     padding:0 30px;
     display:flex;
     flex-direction:row;
}
.iy-7{
    display:block;
    width:20%;
    height:100%;
}
.iy-8{
    display: block;
    width:30%;
    height:54px;
    background:#ff9c00;
    text-align:center;
    line-height: 54px;
    margin:20px 0 0 15%;
    border-radius:20px;
    color:#fff;
     border:none;
}
.iy-9{
    height:100px;
    width:100%;
    background: #f39d0a;
    text-align: center;
    line-height: 100px;
    font-size:30px;
    color:#fff;
    position:fixed;
    bottom:0;
    left:0;
   
}
.a{
    background:#bbbbbb;
}

.zcontainer{
  width:100%;
  height:100%;
  text-align: center;
}
.zt-1{
  width:100%;
  height:100%;
  background:#000;
  opacity:0.4;
  z-index:666;
  position:fixed;
  left:0;
  top:0;
}
.zt-2s{
  width:548px;
  height:292px;
  background:#fff;
  z-index:888;
  position:fixed;
  left:50%;
  top:50%;
  margin:-146px 0 0 -274px;
  border-radius: 30px;
  display: flex;
  flex-direction: column;
}
.zt-rm{
  width:50px;
  height:50px;
  border-radius: 50%;
  position: absolute;
  right:-25px;
  top:-25px;
  border:3px solid #ff9c00;
  text-align: center;
  line-height: 50px;
  background:#fff;
  color:#ff9c00;
}
.zt-3{
  font-size:36px;
  margin:20px 0 10px 0;
  color:#000;
}
.zt-5{
  font-size:30px;
  margin:0 0 20px 0;
}
.zt-3s{
  font-size:36px;
  margin:40px 0 10px 0;
}
.zt-4s{
  display: block;
  width:60%;
  height: 80px;
  background:#ff9c00;
  font-size:30px;
  color:#fff;
  line-height:80px;
  margin:0 auto;
  border-radius:40px;
}
</style>


