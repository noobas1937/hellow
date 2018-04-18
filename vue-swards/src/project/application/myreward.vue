<template>
  <div  class="wages-container">

  <div class="wgc-1 column">
    <img class="wgc-1a" src="../../assets/img/3l.png"/>
    <span  class="wgc-1b fs-2 cr-2">总计发放</span>
    <span  class="wgc-1c fs-8 cr-2">{{rginfo.salary}}</span>
  </div>
  <div class="wgc-1-new fs-1 cr-6 row">
     <span class="wgc-1-new-1">奖项</span>
     <span class="wgc-1-new-1">金额</span>
     <span class="wgc-1-new-1">可使用时间</span>
  </div>
  <div class="wgc-2 column">

     <div class="wgc-2ass bg-1 fs-1" v-for="(item,index) in rginfo.record" :key="index">
       <span class="wgc-2as-1 cr-6">{{item.remark}}</span>
       <span class="wgc-2as-2 cr-2">{{item.credits}}</span>
       <span class="wgc-2as-3 cr-5 fs-1">{{item.unfreeze_time}}</span>
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
         rginfo:{},
         flagmodal:false,
       }
     },
     components:{
        modal,
     },
     created(){
       this.getMyRewardsInfo();
     },
     methods:{
       getMyRewardsInfo(){
          var self=this;
          var eid=localStorage.getItem('eid');
          this.$http.post('?action=user.get.salarydetail',{ user_id: eid,type: 2})
          .then(res=>{
              console.log(res)
              if(res.data.code==3){
                  self.rginfo=res.data.data
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
       }
     }
}
</script>


<style>
 html{
  background: #f5f5f5;
}
.wages-container{
  width:100%;
 
}
.wgc-1{
  
   text-align: center;
   padding:66px 0 86px 0;
 
}
.wgc-1a{
   width:90px;
   height:90px;
   margin: 0 auto;
}
.wgc-1b{
  margin:32px 0 22px 0;
}
.wgc-1c{
  font-size: 46px;
  font-weight: bold;
}
.wgc-2{
  
}
.wgc-2ass{
  padding:20px 30px;
  border-bottom:1px solid #bdbdbd;
}
.wgc-2as-1{
  float:left;
  line-height: 60px;
  display: block;
  width:240px;
  overflow: hidden;
  text-overflow: hidden;
  white-space: nowrap;
}
.wgc-2as-2{
  float:left;
  margin-left:80px;
  line-height: 60px;
}
.wgc-2as-3{
  float:right;
  line-height: 60px;
}
.cover{
    width:100%;
    height:100%;
    position: fixed;
    left:0;
    top:0;

}
.cover-1{
    width:100%;
    height:100%;
    background: #000;
    opacity: 0.4;
    position: fixed;
    left:0;
    top:0;
}
.cover-2{
    width:606px;
    height:444px;
    background: #fff;
    border-radius: 14px;
     display: flex;
    flex-direction: column;
    text-align: center;
    position: fixed;
    left:50%;
    top:50%;
    margin:-222px 0 0 -303px;
    
}
.cover-2a{
  text-align: left;
  margin:40px 0 0 40px;
}
.cover-2b{
  margin-top:40px;
}
.cover-2c{
  margin-top:20px;
  margin-bottom: 60px;
  font-size:50px;
  font-weight: bold;
}
.cover-2d{
  display: flex;
  flex-direction: row;
  margin: 0 auto;
}
.cover-2d-1{
  display: block;
  height:94px;
  width:200px;
  line-height: 94px;
  text-align: center;
  border-radius: 12px;
}
.cover-2d-2{
   border:1px solid #bdbdbd;

}
.cover-2d-3{
  margin-left:40px;
}
.wgc-1-new{
  
}
.wgc-1-new-1{
  display: block;
  width:33.3%;
  box-sizing: border-box;
  text-align: center;
  line-height: 80px;
  background: #bdbdbd;
}
</style>


