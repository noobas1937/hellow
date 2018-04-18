<template>
<div class="userinfo">
  <div class="us-1">
      <span class="us-2">
          <img src="../assets/img/tx1.jpg" alt="">
      </span>
      <span class="us-3">
          {{name}}{{level}}
      </span>
      <span class="us-4"><b>{{status}}</b></span>
  </div>
  <div class="us-5" @click="myOrder()">
      <span>我的订餐</span>
      <span class="us-7">></span>
  </div>
  <div class="us-6" @click="siteOrder()" v-show=' levelstatus'>
    <span>站点订餐</span>
    <span class="us-7">></span>
  </div>
  <div class="us-6" @click="findinfo()">
    <span>个人信息</span>
    <span class="us-7">></span>
  </div>
  
</div>

</template>

<script>
export default {
   data(){
       return{
           name:'',
           level:'',
           status:'',
           uid:'',
           levelstatus:false,
       }
   },
   created(){
       this.getInfo();
   },
   methods:{
       myOrder(){
           this.$router.push({ name: 'Morder', params: {}})
       },
       siteOrder(){
           this.$router.push({ name: 'Adorder', params: {}})
       },
       renzheng(){
            this.$router.push({ name: 'Identity', params: {}})
       },
       findinfo(){
            this.$router.push({ name: 'Userinfo', params: {}})
       },
       getInfo(){
            var ide=localStorage.getItem('identity');
            var uname=localStorage.getItem('uname');
            var umaster=localStorage.getItem('umaster');
            var uid=localStorage.getItem('uid');
            this.name=uname;
            this.status=ide;
             console.log(umaster)
             if(umaster=='是'){
                 this.level='(站长)';
                 this.levelstatus=true;
             }
             
       }
   }
}
</script>

<style>
.userinfo{
  overflow: hidden;
}

.us-2{
   margin-top:46px;
}
.us-2 img{
    width:124px;
    height:124px;
    border-radius: 50%;
}
.us-3{
   font-size:36px;
}
.us-4{
font-size:30px;
  margin-top:10px;
}
.us-4 b{
    padding:0 30px;
    background: #ff9c00;
    color:#fff;
    border-radius: 10px;
}
.us-5,.us-6{
    font-size:28px;
    width:100%;
    height:92px;
    border-bottom:1px solid #dcdcdc; 
    text-align:left;
    line-height:92px;
    padding:0 30px;
}
.us-7{
  margin-left:550px;
}
.zcontainer{
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
.zt-2{
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
  margin:40px 0 40px 0;
}
.zt-4{
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
.us-1{
     width:100%;
     height:322px;
     background:url('../assets/img/ubg.jpg');
     background-size:cover;
     display: flex;
     flex-direction: column;
}
</style>



