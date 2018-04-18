<template>
<div class="userinfo">
  <div class="us-5">
      <span class="us-s">姓名</span>
      <span class="us-71">{{userinfo.name}}</span>
  </div>
  <div class="us-6" >
    <span class="us-s">性别</span>
    <span class="us-71">{{userinfo.sex}}</span>
  </div>
  <div class="us-6">
    <span class="us-s">手机号</span>
    <span class="us-71">{{userinfo.mobile}}</span>
  </div>
  <div class="us-6">
    <span class="us-s">身份证号</span>
    <span class="us-71">{{userinfo.idcard}}</span>
  </div>
  <div class="us-6">
    <span class="us-s">入职信息</span>
    <span class="us-71">{{userinfo.type}}</span>
  </div>
   <div class="us-6">
    <span class="us-s">工作状态</span>
    <span class="us-71">{{userinfo.workstatus}}</span>
  </div> 
  <div class="us-6">
    <span class="us-s">所属站点</span>
    <span class="us-71">{{userinfo.site_name}}</span>
  </div>
  <div class="quit" v-show="false">退出登录</div>
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
           userinfo:{}
       }
   },
   created(){
       this.getInfo();
   },
   methods:{
      getInfo(){
          var uid=localStorage.getItem('uid');
          var self=this;
          this.$http.post('/api/auth/isauth',{userid:uid})
          .then(response=>{
             console.log(response.data);
             self.userinfo=response.data.data;
          })
          .catch(error=>{
              console.log(error);
          })
      }
   }
}
</script>

<style>
.userinfo{
  overflow: hidden;
}

.us-5,.us-6{
    font-size:28px;
    width:100%;
    height:92px;
    border-bottom:1px solid #dcdcdc; 
    text-align:left;
    line-height:92px;
    padding:0 30px;
    display:flex;
    flex-direction: row;
}
.us-s{
 display:block;
  width:40%;
  box-sizing: border-box;
}
.us-71{
  display:block;
  width:50%;
  text-align:right;
  box-sizing: border-box;

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
.quit{
  width: 100%;
  height:90px;
  text-align: center;
  line-height: 90px;
  background:#ff9c00;
  color:#fff;
  font-size:34px;
  position: fixed;
  left:0;
  top:0;
}
</style>



