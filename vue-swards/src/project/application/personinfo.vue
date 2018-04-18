<template>
<div class="mypersoninfo fs-0 bg-8 column">

  <div class="mps-1 bg-1" style="padding:30px;">
     <span class="lef" style="line-height:64px;">头像</span>
     <div class="rig">
      <img :src="rinfo.wechatuser?rinfo.wechatuser.avatar:''" class="userinfo-avatar"/>
      <img src="https://api.nacy.cc/wxappimg/rig.png" class="mps-1b-rig" />
     </div>
  </div>

  <div class="mps-1 mps-1s cr-2 bg-1">
     <span class="lef">真实姓名</span>
     <span class="rig cr-6">{{rinfo.name}}</span>
  </div>

   <div class="mps-1 mps-1s cr-2 bg-1">
     <span class="lef">所属站点</span>
     <span class="rig cr-6">{{rinfo.site}}</span>
  </div>

  <div class="mps-1 mps-1s cr-2 bg-1">
     <span class="lef">职务</span>
     <span class="rig cr-6">{{rinfo.describe}}</span>
  </div>


   <div class="mps-1 mps-1s cr-2 bg-1" @click='updateMyPhone' style="margin:20px 0;border-top:1px solid #bdbdbd;">
     <span class="lef mps-1a">手机号</span>
     <div class="rig mps-1b cr-6">
       <span >{{rinfo.contact_moblie}}</span>
       <img src="https://api.nacy.cc/wxappimg/rig.png" class="mps-1b-rig"/>
     </div>
  </div>

  <div class="mps-1 mps-1s cr-2 bg-1">
     <span class="lef">身份证号</span>
     <span class="rig cr-6">{{rinfo.idcard}}</span>
  </div>

  <div class="mps-1 mps-1s cr-2 bg-1">
     <span class="lef">证件照片</span>
     <span class="rig cr-6">待完善</span>
  </div>

  <div class="mps-1 mps-1s cr-2 bg-1" @click="addBankCard">
     <span class="lef">银行卡</span>
     <div class="rig mps-1b cr-6">
       <span >添加</span>
       <img src="https://api.nacy.cc/wxappimg/rig.png" class="mps-1b-rig"/>
     </div>
  </div>

 
 

   <modal :modalshow="flagmodal"></modal>
</div>

  
</template>




<script>
import modal from'@/components/modal'

export default {
    data(){
        return{
            avatarUrl:'',
            rinfo:{},
            flagmodal:false,
        }
    },
    components:{
        modal,
    },
    created(){
       this.getUserInfo();
    },
    methods:{
        updateMyPhone(){
          var self=this;
         this.$router.push({
           name:'settelphone',
           params: { 
               tel:self.rinfo.contact_moblie
            }
        })
      },
       getUserInfo(){
          var self=this;
          var eid=localStorage.getItem('eid');
          this.$http.post('?action=user.get.myinfo',{user_id:eid}).then(res=>{
               if(res.data.code==3){
                  self.rinfo=res.data.data
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
          }).catch(err=>{
              console.log(err)
          })
       },
       addBankCard(){
           this.$router.push({
             name:'addbankcard'
           })
       }
    }
}
</script>



<style>
html{
  background:#f5f5f5;
}
.mypersoninfo{
  width:100%;
  display: flex;
  flex-direction: column;
}
.mps-1{
  padding:0 30px;
  border-bottom:1px solid #bdbdbd;
}
.mps-1b-rig{
  width:40px;
  height:40px;
  vertical-align: middle;
}
.userinfo-avatar {
  width: 128px;
  height: 128px;
  border-radius: 50%;
  margin:0 auto;
  border:solid 3px #ffd13e;
  vertical-align: middle;
}
.mps-1s{
  line-height: 90px;
}

</style>


