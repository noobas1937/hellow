<template>
   <div class="wages-container">

       <div  class="wgc-1 column">
           <img  class="wgc-1a" src="../../assets/img/2l.png" alt="">
           <span class="wgc-1b fs-0">总计发放</span>
           <span class="wgc-1c fs-3">￥{{rmb.salary}}</span>
       </div>

       <div class="wgc-2amm bg-1 fs-1" v-for="(item,index) in rmb.record" :key="item.record_id" @click="goWagesDel(item.record_id)">
         <span class="wgc-2a-1m cr-6 lef">{{item.remark}}</span>
         <img src="../../assets/img/rig.png" class="wgc-2a-3img"/> 
         <span class="wgc-2a-2m cr-2 left">{{item.credits}}</span>
         <div class="wgc-2a-3m cr-5 rig" v-if="item.isconfirm==0">确认</div>
         <div class="wgc-2a-3ms bg-7 cr-1 rig" v-else-if="item.isconfirm>0">已确认</div>
      </div>
    <modal :modalshow="flagmodal"></modal>
   </div>
</template>


<script>
import modal from'@/components/modal'

export default {
    data(){
        return{
           rmb:{},
           flagmodal:false,
        }
    },
    components:{
        modal,
    },
    created(){
       this.getMyWagesInfo();
    },
    methods:{
      goWagesDel(id){
        console.log(id);
        this.$router.push({
          name:'mywagesdel',
          params:{
                id:id
          }
        })
      },
      getMyWagesInfo(){
          var self=this;
          var eid=localStorage.getItem('eid');
          this.$http.post('?action=user.get.salarydetail',{user_id:eid,type:1}).then(res=>{
              if(res.data.code==3){
                  self.rmb=res.data.data
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
 
.wages-container{
  width:100%;
  background: #f5f5f5;
}
.wgc-1{
  
   text-align: center;
   padding:26px 0 35px 0;
   border-bottom:1px solid #bdbdbd;
}
.wgc-1a{
   width:90px;
   height:90px;
   margin: 0 auto;
}
.wgc-1b{
  margin:14px 0 10px 0;
  font-size:20px;
}
.wgc-1c{
  
  font-weight: bold;
  font-size: 40px;
}
.wgc-2{
   display: flex;
   flex-direction: column;
}

.wgc-2amm{
  padding:0 30px;
  height:100px;
  border-bottom:2px solid #bdbdbd;
}
.wgc-2a-1m{
  display: block;
  width:250px;
  line-height: 100px;
}
.wgc-2a-2m{
  line-height: 100px;
}
.wgc-2a-3img{
  width:40px;
  height:40px;
  float:right;
  vertical-align: middle;
  margin-top: 30px;
}
.wgc-2a-3m{
  float:right;
  width:120px;
  height:60px;
  border-radius: 16px;
  text-align: center;
  line-height: 60px;
  border:2px solid #ff0000;
  margin-top: 20px;
}
.wgc-2a-3ms{
  float:right;
  width:120px;
  height:60px;
  border-radius: 16px;
  text-align: center;
  line-height: 60px;
  margin-top: 20px;
}
.wgc-2a-3s{
   float:right;
  display: block;
  width:120px;
  height:60px;
  border-radius: 16px;
  text-align: center;
  line-height: 60px;
  border:1px solid #bdbdbd;
  pointer-events: none;

}
.wgc-2a-3b{
  float:right;
 
  width:120px;
  height:600px;
  border-radius: 16px;
  text-align: center;
  line-height: 60px;
  border:1px solid #bdbdbd;
  pointer-events: none;
}
.lef{
   float:left;
}
rig{
   float:right;
}


</style>
