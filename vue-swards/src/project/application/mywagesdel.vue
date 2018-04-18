<template>
<div class="mwd column">
  <div class="mwd-1 column">
    <div class="mwd-1a">
      <div class="mwd-1a-1 lef">
        <span class="fs-3 cr-2">{{wdi.head.name}} </span>
        <span class="fs-1 cr-6"> {{wdi.head.idcard}}</span> 
      </div>
       <span class="mwd-1a-2 fs-1 cr-2 rig">{{wdi.tail.remark}}</span>
    </div>

    <span class="mwd-1b fs-1 cr-2">{{wdi.head.describe}}<span class="fs-2 cr-6">（{{wdi.head.join_date}}入职）</span></span>
    <span class="mwd-1c  fs-1 cr-2">{{wdi.head.site?wdi.head.site:''}}<span class="fs-2 cr-6">{{wdi.head.d4?'('+wdi.head.d4+')': ' '}}</span></span>
  </div>

  <div class="mwd-2 column">

    <div class="mwd-2a" v-for="(item,index) in wdi.detail" :key="index">
      <span class="mwd-2a-1 cr-6 fs-1" >{{item.key}}</span>
      <span class="mwd-2a-2 cr-2 fs-1">{{item.value}}</span>
    </div>
 
  </div>
   
  <div  class="mwd-3">
    <span class="mwd-3d fs-m cr-5" @click='goMyOwnReward'>点击查看年终奖、值班津贴、持续贡献奖、返岗奖励。</span>
    <span class="mwd-3c fs-m cr-11 bg-1">如有疑问，请联系站长或者拨打客服热线电话18062640522</span>
    <span class="mwd-3a lef fs-2 cr-2">实发工资<span class="cr-5 fs-3 wgss">{{wdi.tail.credits}}</span></span>
    <span class="mwd-3b cr-1 fs-3 rig bg-5"  v-if="wdi.tail.isconfirm==0" @click="checkWages">确认</span>
    <span class="mwd-3b wgc-2a-3bs fs-3 cr-6 rig "  v-else-if="wdi.tail.isconfirm==1">已确认</span>
    <span class="mwd-3b wgc-2a-3bs fs-3 cr-6 rig "  v-else-if="wdi.tail.isconfirm==2">已确认</span>
  </div>

  <div class="cover" v-show="false">
      <div class="cover-1"></div>

      <div class="cover-2 column">
         <span class="cover-2a fs-5 cr-2">请您核对工资信息</span>
         <span class="cover-2b fs-3 cr-6">{{wdi.tail.remark}}</span>
         <span class="cover-2c ">￥{{wdi.tail.credits}}</span>
         <div class="cover-2d fs-6 row">
           <span class="cover-2d-1 cover-2d-2" @click='thinkMore'>我再想想</span>
           <span class="cover-2d-1 cover-2d-3 cr-1 bg-5" @click="msWages(wdi.tail.record_id)">确认</span>
          </div>
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
          mid:this.$route.params.id,
          wdi:{},
          flagmodal:false,
        }
    },
    components:{
        modal,
    },
    created(){
       this.getMessage();
    },
    methods:{
        getMessage(){
            var rid=this.$route.params.id;
            var self=this;
            var eid=localStorage.getItem('eid');
            this.$http.post('?action=user.post.singlsalarydetail',{record_id:rid,user_id:eid})
            .then(res=>{
              if(res.data.code== 3) {
                  self.wdi=res.data.data;
              } else if(res.data.code==1122){
                  self.flagmodal=true;
                 
             }
             else{
                  self.$toast({
                     title:'消息提示',
                     content:res.data.msg
                 })
             }
            console.log(res)})
            .catch(err=>{console.log(err)})
        },
        checkWages(){
            var rid=this.$route.params.id;
            var self=this;
            var eid=localStorage.getItem('eid');
            this.$http.post('?action=user.post.userpointconfirm',{recordid:rid,user_id:eid})
            .then(res=>{
              if(res.data.code== 3) {
                   self.$toast({
                     title:'消息提示',
                     content:res.data.msg
                 })
              }  
             else{
                  self.$toast({
                     title:'消息提示',
                     content:res.data.msg
                 })
             }
            console.log(res)})
            .catch(err=>{console.log(err)})
             
        },
        thinkMore(){

        },
        msWages(id){

        },
        goMyOwnReward(){
            this.$router.push({
               name:'myreward'
            })
        }
    }
}
</script>



<style>

.mwd{
  width:100%;
 
}
.mwd-1{
  padding:30px;
 
  border-bottom: 1px solid #bdbdbd;
  margin-bottom: 30px;
}
.mwd-1a{

}
.mwd-1b{
  margin-top:20px;
}
.mwd-1c{
  margin-top:14px;
}
.mwd-2{
 
  margin-bottom:300px;
}
.mwd-2a{
  border-bottom: 1px solid #bdbdbd;
  line-height: 80px;
}
.mwd-2a-1{
  float:left;
  margin-left:30px;
}
.mwd-2a-2{
  float:right;
  margin-right:30px;
}
.mwd-3{
  width:100%;
  
  background:#fff;
  position: fixed;
  left:0;
  bottom:0;
  border-top:1px solid #bdbddb;
}
.mwd-3a{
  display: block;
  line-height: 100px;
  margin-left:30px;
  background:#fff;
}

.mwd-3b{
   display: block;
   width:250px;
   height:100px;
   text-align: center;
   line-height: 100px;
  
}
.wgss{
  margin-left:15px;
  font-weight: bold;
}
.cover{
    width:100%;
    height:100%;
    position:fixed;
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
.wgc-2a-3bs{
  background:#f5f5f5;
  pointer-events: none;
}
.mwd-3c{
  height:80px;
  display: block;
  padding:0 30px;
  
  color:#e72142;
  font-size: 22px;
  text-align: left;
  line-height: 80px;
  border-bottom:1px solid #bdbdbd;
}
.mwd-3d{
  height:80px;
  display: block;
  padding:0 30px;
  background:#ffe3cc;
  color:#e72142;
  
  text-align: left;
  line-height: 80px;
  border-bottom:1px solid #bdbdbd;
}

</style>


