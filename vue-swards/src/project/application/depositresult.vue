<template>
   <div class="r-container bg-1 column">
   

  <div class="wde-3 column">
    
    <div class="wde-3b" v-for="(item,index) in desinfo.record" :key="index">
      <div class=" bg-1 wde-3b-1">
        <span class="cr-6 fs-1 wde-3b-1a lef">{{item.create_time}}</span>
        <span class="cr-2 fs-1 wde-3b-1b lef">￥{{0-item.money}}</span>
        <span class="cr-6 fs-1 wde-3b-1c">{{item.status==0?'提现中':'已提现'}}</span>
      </div>
    </div>
  </div>
 <modal :modalshow="flagmodal"></modal>
 <nrecord :showr="rflag"></nrecord>

</div>

</template>



<script>
import modal from'@/components/modal'
import nrecord from '@/components/nrecord'
export default {
    data(){
        return{
          msg:this.$route.params.id,
          desinfo:{
          },
          flagmodal:false,
          rflag:false,
        }
    },
    components:{
        modal,
        nrecord
    },
    beforeCreate(){
    
    },
    created(){
      this.getMessage();
    },
    methods:{
      getMessage(){
        var self=this;
        var eid=localStorage.getItem('eid'); 
        this.$http.post('?action=user.get.balancedetail',{user_id: eid})
        .then(res=>{
          if(res.data.code==3){
                if(res.data.data.record.length>=1){
                   self.desinfo=res.data.data;
                }
                else{
                   self.rflag=true
                }
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
          })
        .catch(err=>{console.log(err)})
        }
    }
}
</script>



<style>
.r-container{
  width:100%;
  
 
}
.wde-3{
 
}
.wde-3a{
  display: block;
  margin-bottom:24px;
  padding:0 30px;
 
}
.wde-3b{
  border-top: 1px solid #bdbdbd;
  border-bottom: 1px solid #bdbdbd;
  padding:0 30px;
}
.wde-3b-1{
   line-height: 100px;
}
.wde-3b-1a{
  float:left;
}
.wde-3b-1b{
  float:left;
  margin-left:120px;
}
.wde-3b-1c{
  float:right;
}
.explain{
  display: flex;
  flex-direction: column;
  padding:0 30px;
}
.ex-txt{
  display: inline-block;
  line-height: 60px;
}

</style>


