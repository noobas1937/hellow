<template>
   <div class="r-container column">
   
    
    <div class="wgc-2a bg-1 fs-1" v-for="(item,index) in grainfo.record" :key="index">
        <div class="wgc-2a-1">
          <span class="cr-2">{{item.descript}}</span>
          <span class="cr-6">{{item.create_date}}</span>
       </div>
       <span class="wgc-2a-2 cr-2">{{item.credits>0?'+'+item.credits:item.credits}}</span>
    </div> 
 

  <nrecord :showr="rflag"></nrecord>
  <modal :modalshow="flagmodal"></modal>
 </div>

</template>



<script>
import modal from'@/components/modal'
import nrecord from '@/components/nrecord' 


export default {
    data(){
        return{
          msg:this.$route.params.id,
          grainfo:{},
          flagmodal:false,
          rflag:false,
        }
    },
    components:{
        modal,
        nrecord
    },
    beforecreate(){
       //this.getMessage()
    },
    created(){
      this.getMessage();
    },
    methods:{
        getMessage(){
            var self=this;
            var eid=localStorage.getItem('eid'); 
            this.$http.post('?action=user.get.pointdetail',{user_id: eid})
            .then(res=>{
              if(res.data.code==3){
                 if(res.data.data.record.length>=1){
                   self.grainfo=res.data.data;
                }
                else{
                   self.rflag=true
                }

              } else if(res.data.code==1122){
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
  display: flex;
  flex-direction: column;
 
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


.wgc-2{
   display: flex;
   flex-direction: column;
}
.wgc-2a{
  padding:20px 30px;
  border-bottom:1px solid #bdbdbd;
}
.wgc-2a-1{
  float:left;
  line-height: 40px;
  display: flex;
  flex-direction: column;
  max-width: 480px;
  overflow: hidden;
  text-overflow: hidden;
  white-space: nowrap;
}
.wgc-2a-2{
  float:right;
 
  line-height: 80px;
}

</style>


