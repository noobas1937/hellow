<template>
  <div class="ms-contain">

      <div class="ms-icon">
          <img src="../../assets/img/ides.jpg" alt="" class="ms-icon-img">
      </div>

      <div class="idt-1 bg-1">
          <img src="../../assets/img/idt.png" class="idt-1a"/>
          <input class="idt-1b fs-1 cr-6" placeholder='请输入身份证号码' v-model='getIdCard' ></input>
      </div>

      <div class="ms-jh bg-9 cr-1 fs-3" @click="findMyInfo">
          <span>激活</span>
      </div>
      
      <div class="fs-1 cr-5 warn-title">
          请输入本人身份证号，并仔细确认是否有误，如仍然无法激活，请联系站长。请务必在入职后3天内完成激活，以免影响次月工资发放及参与夺宝活动。
      </div>
  </div>
  
</template>



<script>
export default {
    data(){
        return{
           getIdCard:''
        }
    },
    methods:{
       findMyInfo(){
         var idcard=this.getIdCard;
         var reg=/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/; 
         var self=this;
         if(reg.test(idcard)){
            this.$http.post('?action=user.get.userinfo',{idcard:idcard})
            .then(res=>{
             console.log(res.data)
               if(res.data.code==3){
                var data=res.data.data;
                 localStorage.setItem('idcard',data.bank_card);
                 localStorage.setItem('mobile',data.contact_moblie);
                 localStorage.setItem('site',data.site);
                 localStorage.setItem('name',data.name);
                 localStorage.setItem('eid1',data.id);
                         
                         self.$router.push({
                             name:'msinfo'
                         })
                    
               
                 
                 }else{
                     self.$toast({
                     title:'消息提示',
                     content: res.data.msg,
            
                  })
                 }
            })
           .catch(err=>{
             console.log(err)
         })
        }
        
        else{
           self.$toast({
              title:'消息提示',
              content: '您的身份证输入有误',
              //type: 'warning',
              //onShow: ()=>{
              //    console.log('on toast show')
             // },
             // onHide: ()=>{
             //     console.log('on toast hide')
             // }
          })
        } 
         
       }
    }
}
</script>



<style>
html{
    background:#f2f2f4;
}
.ms-contain{
    
   
    display:flex;
    flex-direction: column;
    padding:0 30px;
}
.ms-icon{
    
    height:360px;
    position: relative;
    
}
.ms-icon-img{
    width:184px;
    height:184px;
    position: absolute;
    left:50%;
    top:50%;
    margin:-92px 0 0 -92px;
}
.idt-1{
 
  height:90px;
  display: flex;
  flex-direction: row;
  
  border-radius: 14px;
}
.idt-1a{
  width:34px;
  height:24px;
  margin:33px 0 0 28px;
}
.idt-1b{
  width:520px;
  height:88px;
  line-height: 60px;
  margin-left:30px;
  border:none;
}
.ms-jh{
   height:90px;
   margin-top:80px;
   border-radius: 14px;
   text-align:center;
   line-height:90px;
}
.warn-title{
   margin-top:30px;
}
</style>

