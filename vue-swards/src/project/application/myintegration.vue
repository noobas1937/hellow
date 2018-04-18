<template>
  <div  class="wages-container">

  <div class="wgc-1 column">
    <img class="wgc-1a" src="../../assets/img/1l.png" alt="">
    <span  class="wgc-1b fs-0 cr-2"><span class="fs-3 cr-2">{{money?money:0}}</span>奋斗金</span>
    <span  class="wgc-1cs fs-1 cr-11" @click="goConvertibility">兑换现金并提现</span>
  </div>

  <!-- <div class="wgc-2">
    <div class="app-2a3" @click="useResult">
      <span class="app-2a2 fs-2 cr-2">奋斗金明细</span>
      <div class="app-new-list">
        <img src="../../assets/img/rig.png"  class="app-rig1 app-new-list-2" alt=""/>
      </div>
    </div>

     <div class="app-2a3" @click="depsitResult">
      <span class="app-2a2 fs-2 cr-2">提现记录</span>
      <div class="app-new-list">
          <img src="../../assets/img/rig.png" class="app-rig1 app-new-list-2" alt=""/>
      </div>
    </div> -->
    <div class="mx-title">奋斗金收支明细</div>
    <div class="r-container column">
   
    
    <div class="wgc-2a bg-1 fs-1" v-for="(item,index) in grainfo.record" :key="index">
        <div class="wgc-2a-1">
          <span class="cr-2">{{item.descript}}</span>
          <span class="tm-1 cr-6">{{item.create_date}}</span>
       </div>
       <span class="wgc-2a-2 cr-2">{{item.credits>0?'+'+item.credits:item.credits}}</span>
    </div> 
 

  <nrecord :showr="rflag"></nrecord>
  <modal :modalshow="flagmodal"></modal>
 </div>

<!--       
  </div> -->
  

<modal :modalshow="flagmodal"></modal>
</div>

</template>




<script>
import modal from'@/components/modal'
import nrecord from '@/components/nrecord' 
export default {
   data(){
       return{
           money:'',
           flagmodal:false,
           modalfunc:Function,
           msg:this.$route.params.id,
          grainfo:{},
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
     this.getInfo(),
     this.getMessage();
   },
   methods:{
      
      getInfo(){
          var self=this;
          var eid=localStorage.getItem('eid');
          this.$http.post('?action=user.get.pointdetail',{user_id:eid}).then(res=>{
              console.log(res)
              if(res.data.code==3){
                  self.money=res.data.data.total
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
             
          }).catch(error=>{
              console.log(error)
          })
       },
       goConvertibility(){
         this.$router.push({
          name:'myconvertibility',
          params: {  
            }
        })
       },

       depsitResult(){
        this.$router.push({
          name:'depositresult',
          params: { 
               id:1
            }
        })
       },

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



.wages-container{
  width:100%;
  background: #f5f5f5;
}
.wgc-1{

   text-align: center;
   padding:60px 0 80px 0;
  
}
.wgc-1a{
   width:90px;
   height:90px;
   margin: 0 auto;
}
.wgc-1b{
  margin:36px 0 36px 0;
}
.wgc-1cs{
  display: block;
  width:250px;
  height:60px;
  border:2px solid #e1b858;
  text-align: center;
  line-height: 60px;
  margin:0 auto;
  border-radius: 14px;
  
}

/*收支明细*/

.r-container{
  width:100%;
   display: flex;
  flex-direction: column;
 
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
.mx-title{
  padding:20px 30px;
  border-bottom:1px solid #bdbdbd;
  font-size: 30px;
  color: #494848
}
.tm-1{
      padding-top: 5px;
}

</style>

