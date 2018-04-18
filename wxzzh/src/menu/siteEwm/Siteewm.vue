<template>
  <div class="stm">
      <span class="stm-1">-{{sitename}}-</span>
      <span class="stm-2">站点领餐二维码</span>
      <span class="stm-3">领餐时间:{{date}}</span>
      <span class="stm-5"></span>
      <div class="stm-4">
          <img :src="imgurl" alt="">
      </div>
  </div>
</template>

<script>
export default {
    data(){
        return{
           imgurl:'',
           siteid:'',
           date:'',
           sitename:'',
        }
    },
    created(){
      this.findEwm();
      this.getDate();
    },
    methods:{
      findEwm(){
         this.sitename=localStorage.getItem('sitename');
         this.siteid=localStorage.getItem('bsiteid');
         this.imgurl='https://m.ggjrfw.com/api/order/qrcode?siteid='+this.siteid;
       },
       getDate(){
           var a=new Date();
           var yy=a.getFullYear();
           var mm=a.getMonth()+1;
           var dd=a.getDate();
           if(mm<10){
               var mm='0'+mm;
           }
           if(dd<10){
               var dd='0'+dd;
           }
           this.date=yy+'/'+mm+'/'+dd;
       }
    }
}
</script>

<style>
.stm{
    margin-top:80px;
    display:flex;
    flex-direction:column;
}
.stm-1{
    font-size:32px;
    font-weight:bold;
    color:#ff5a03;
}
.stm-2{
    font-size:28px;
    margin-top:18px;
    margin-bottom:60px;
    color:#4b4b4b;
}
.stm-3{
    display:block;
    width:360px;
    height:50px;
    background:#f5f5f5;
    font-size:28px;
    line-height:50px;
    margin-top:18px;
    color:#ff5a03;
    margin:0 auto;
    border-radius:18px;
}
.stm-4{
    width:520px;
    height:520px;
    background:red;
    margin:0 auto;
    
}
.stm-4 img{
    width:520px;
    height:520px;
}
.stm-5{
    margin-bottom:60px;
}
</style>


