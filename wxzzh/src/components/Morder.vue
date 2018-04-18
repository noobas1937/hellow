<template>
 <div class="msContainer">
    <div class="mc-1" @click="toMorder()">
        <img src="../assets/img/order.png" alt="">
        <span>我的订餐</span>
    </div> 
    <div class="mc-1" @click="toSitewm()">
        <img src="../assets/img/ewm.png" alt="">
        <span>站点二维码</span>
    </div>
    <div class="mc-1" @click="getSys()">
        <img src="../assets/img/saoma.png" alt="">
        <span>扫码领餐</span>
    </div>


 <div class="zcontainers" v-show="active">
   <div class="zt-1"></div>
   <div class="zt-2c">
      <span class="zt-3c">您没有权限查看</span>
      <span class="zt-4c" @click="goidentitys()">好的</span>
      <div class='zt-rm' @click="changeActive()">X</div>
   </div>
  </div> 



 <div class="zcontainers" v-show="active1">
   <div class="zt-1"></div>
   <div class="zt-2c">
      <span class="zt-3c">{{finfo}}</span>
      <span class="zt-4c" @click="goidentitys1()">好的</span>
      <div class='zt-rm' @click="changeActive1()">X</div>
   </div>
  </div> 

 <div class="zcontainers1" v-show="active2">
   <div class="zt-1"></div>
   <div class="zt-2cs">
      <span class="zt-3c">请核对当前领取商品</span>
      <img :src="Qcodeinfo.image" alt="" class='zttimg'>
      <span class="zt-3c">{{Qcodeinfo.name}}</span>
      <span class="zt-4c" @click="msorder()">确定领取</span>
      <div class='zt-rm' @click="changeActive2()">X</div>
   </div>
  </div> 


 </div>
</template>

<script>
import wx from 'weixin-js-sdk'
 //console.log(wx);
 export default {
    data(){
      return{
        uaction:true,
        active:false,
        active1:false,
        active2:false,
        finfo:'',
        Qcodeinfo:{},
        iresult:''
      }
    },
    created(){
    
    
    },
    ready(){
      
    },
    methods: {
       toMorder(){
            localStorage.removeItem('otime');
            localStorage.removeItem('otime1');
            localStorage.removeItem('ams');
            localStorage.removeItem('bms');
           this.$router.push({ name: 'Morder', params: {}})
       },
       toSitewm(){
           var umaster=localStorage.getItem('umaster');
           //console.log(umaster)
           if(umaster=='是'){
            this.$router.push({ name: 'Siteewm', params: {}})
           }
           else{
               this.active=true;
           }
          
       },
   getSys(){
    var self=this;
    this.$http.get('api/auth/getjsconfig?url=https://m.ggjrfw.com/#/')
    .then(response=>{
      console.log(response.data);
      var wxdata=response.data.data;
      wx.config({
        debug: false,
        appId: wxdata.appId,
        timestamp:wxdata.timestamp,
        nonceStr: wxdata.nonceStr,
        signature: wxdata.signature,
        jsApiList: [
            'scanQRCode',   
          ] 
          });
      wx.scanQRCode({
        needResult: 1, 
        scanType: ["qrCode","barCode"], 
        success: function (res) {
        var uId=localStorage.getItem('uId')
        //console.log(uId);
        var result = res.resultStr;
        self.iresult=result;
        var a=new Date();
        var yy=a.getFullYear();
        var mm=a.getMonth()+1;
        var dd=a.getDate()
        if(mm<10){
          var mm='0'+mm;
        }
        if(dd<10){
          var dd='0'+dd;
        }
        var dates=yy+'-'+mm+'-'+dd;
       // alert(dates);
        //console.log(result); 
        //alert(result);
         self.$http.post('api/order/riderdayorder',{userid:uId,date:dates})
             .then(response=>{
               //console.log(response.data);
               //console.log('扫码测试');
               //alert(response.data.data.name)
               self.Qcodeinfo=response.data.data;
               self.active2=true;
               //self.finfo= response.data.mesg; 
             })
             .catch(error=>{
               //alert(error)
               console.log(error);
                self.active2=true;
             })
        
        }
        });
    })
     .catch(error=>{
      console.log(error)}
      )   
       },
      msorder(){
        var self=this;
         var iresult=this.iresult;
         //alert(iresult);
         
         var uId=localStorage.getItem('uId');
        // alert(uId);
         this.$http.get('api/order/codescan?siteid='+iresult+'&userid='+uId)
         .then(response=>{
          // alert(response.data.code);
          // alert(response.data.mesg);
           //document.write(response.data.mesg+'-'+response.data.code);
           if(response.data.code==100000){
               self.active2=false;
               self.active1=true;
               self.finfo= response.data.mesg; 
           }
           else{
               self.active2=false;
               self.active1=true;
               self.finfo= response.data.mesg; 
           }
         })
         .catch(error=>{
           console.log(error);
         })
       },
       goidentitys(){
          this.active=false;
       },
       changeActive(){
          this.active=false;
       },
       goidentitys1(){
          this.active1=false;
       },
       changeActive1(){
          this.active1=false;
       },
       changeActive2(){
          this.active2=false;
       }
    },
  }
</script>

<style>
.msContainer{
  width:100%;
  height:200px; 
  background:#fff;
  box-sizing: border-box; 
  display: flex;
  flex-direction: row;
  -moz-flex-direction: row;
  overflow: hidden;
}
#mc-2{
    width:33.3%;
    height:100%;
    padding-top:40px;
    color:#4b4b4b;
}
.mc-1{
    padding-top:40px;
    width:100%;
    height:100%;
    display: flex;
    flex-direction: column;
   -moz-flex-direction: column;
}
.mc-1 img{
    width:78px;
    height:78px;
    margin:0 auto;
 }
 .mc-1 span{
     margin-top:26px;
     font-size:26px;
 }
 .zcontainer{
  width:100%;
  height:100%;
 
}
.zcontainers1{
  width:100%;
  height:100%;
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
.zt-2c{
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
.zt-2cs{
  width:548px;
  height:680px;
  background:#fff;
  z-index:1888;
  position:fixed;
  left:50%;
  top:50%;
  margin:-340px 0 0 -274px;
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
.zt-3c{
  font-size:36px;
  margin:60px 0 40px 0;
}
.zt-4c{
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
.zttimg{
  width:260px;
  height:260px;
  margin:0 auto;
}
</style>