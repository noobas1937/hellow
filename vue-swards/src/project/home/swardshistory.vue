<template>
<div class="swards-history">
  <div class="ssh" v-for="(item,index) in glswd" :key="index">
    <div class="ssh-1 bg-1">
     <span class="ssh-1a fs-2 cr-2">第{{item.lucky_draw_id}}期</span>
     <span class="ssh-1b fs-0 cr-6">开奖时间：{{item.create_date}}</span>
     <img class="ssh-1c" src="../../assets/img/rig.png" />
    </div>
    <div class="ssh-2 bg-1 row">
      <img :src="item.award_img" class="ssh-2a" />
      <div class="ssh-2b column">
        <span class="ssh-2b-1 fs-3 cr-2 ">{{item.award_name}}</span>
        <span class="ssh-2b-2 fs-1 cr-6" v-if="eflag==item.employee_id">获奖者：<span class="cr-5">{{item.employee_name}}({{item.site}})</span></span>
        <span class="ssh-2b-2 fs-1 cr-6" v-else>获奖者：{{item.employee_name}}({{item.site}})</span>
         <span class="ssh-2b-4 fs-1 cr-1 bg-9" v-if="item.status==2">获得{{item.przecredit}}奋斗金</span>
          <span class="ssh-2b-4 fs-1 cr-1 bg-5" v-if="item.status==1">获得奖品</span>
        <span class="ssh-2b-3 fs-1 cr-6 bg-8">幸运号：<span>{{item.lucky_number}}</span></span>
      </div>
    </div>
  </div>
<nrecord :showr="rflag"></nrecord>
</div>

</template>




<script>
import nrecord from '@/components/nrecord'

export default {
  data(){
      return{
        glswd:{},
        rflag:false,
        eflag:'',
      }
  },
   components:{
      nrecord
    },
  created(){
    this.getHistorySwards();
  },
  methods:{
    getHistorySwards(){
         this.eflag=localStorage.getItem('eid');
        var self=this;
        this.$http.post('?action=lucky.get.luckyhistory',{})
        .then(res=>{
            if(res.data.code==3){
                
                if(res.data.data.length>=1){
                   self.glswd=res.data.data;

                }
                else{
                   self.rflag=true
                }
               }
             
             else{
                  self.$toast({
                     title:'消息提示',
                     content:res.data.msg
                 })
             }
        })
        .catch(err=>{})
    }
  }
}
</script>




<style>
 html{
   background: #dbdbdb;
}
.swards-history{
   width:100%;
}
.ssh{
  margin-bottom: 30px;
}
.ssh-1{
  padding:0 20px;
  height:96px;
  line-height: 96px;
  border-bottom:1px solid #dbdbdb;
}
.ssh-1a{
 float:left;
}
.ssh-1b{
 float:left;
 margin-left:46px;
}
.ssh-1c{
 float:right;
 width:32px;
 height:32px;
 vertical-align: middle;
 margin-top:30px;
}
.ssh-2{
  padding:30px 30px;
  
}
.ssh-2a{
  width:165px;
  height:165px;
}
.ssh-2b{
  margin-left: 44px;
  
}
.ssh-2b-1{
  margin-top:4px;
  max-width: 450px;
  overflow: hidden;
  text-overflow: hidden;
  white-space: nowrap;
}
.ssh-2b-2{
  margin-top:10px;
}
.ssh-2b-4{
  margin-top:10px;
  padding:4px 10px;
  align-self:left;

}
.ssh-2b-3{
  margin-top:10px;
  max-width: 450px;
  overflow: hidden;
  text-overflow: hidden;
  white-space: nowrap;
}

</style>


