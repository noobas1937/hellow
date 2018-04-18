<template>
<div class="nidentity-container" :style="'height:'+newheight+'px;'">
 <form   @submit.prevent="goNextTip" name="form1">  
  <wv-group title="1.请填写员工信息">
   <wv-input label="姓名" :labelWidth="80" placeholder="请输入真实姓名" v-model="uname" :value="uname" :required="true" :validate-mode="{onFocus: false, onBlur: true, onChange: false}"></wv-input>
   <wv-input label="职务" :labelWidth="80"  :readonly="true" :value="uposition" v-model="uposition"  @click.native="getPositionInfo" :validate-mode="{onFocus: false, onBlur: true, onChange: false}"></wv-input>
   <wv-input label="部门"  :labelWidth="80" :readonly="true" :value="descb" v-model="descb"   @click.native="getDiscribeInfo" :validate-mode="{onFocus: false, onBlur: true, onChange: false}"/>
  </wv-group>
  <span class="warntit cr-6 fs-1">请填写真实有效的信息，以确保更快的通过审核。</span>
  <wv-group title="2.请绑定本人有效手机号">
    <wv-input label="手机号" :labelWidth="80" :value="utelphone" placeholder="请输入手机号" v-model="utelphone">
    <button class="weui-vcode-btn" slot="ft" @click="getCode" value="0">{{btntxt}}</button >
  </wv-input>
  <wv-input label="验证码" :labelWidth="80" class="weui-cell_vcode" :value="ucode" placeholder="请输入验证码" v-model="ucode"> 
   </wv-input>
  </wv-group>
  
  
  

  
   
  

  <span class="warntit cr-11 fs-1">为了您的资金安全，请填写您本人正在使用的有效手机号码。</span>

  <div class="msnInfo">
      <button class="next-click bg-9 cr-1 fs-3" type="submit" :value="nextBtn" :class="btndisable?'a-btn':'b-btn'">下一步</button>
  </div>
  </form>
 <vue-pickers :show="show1" :selectData="pickData1" v-on:cancel="close"  v-on:confirm="confirmFn"></vue-pickers>
 <mypicker :disableBtn="showBtn" :showPicker="showr" :pickerInfo="initInfo" :pickerSelects="selects" @addInfo="changeAddInfo" @subInfo="changeSubInfo" @cancelBtn="cancelSelect" @confirmBtn="confirmSelect"></mypicker>
</div> 
</template>



<script>
 import mypicker from '../../components/mypicker'
 import VuePickers from 'vue-pickers'
 export default {
    
   data(){
       return{
           nextBtn:'',
          showBtn:true,
          show1:false,
          newheight:document.documentElement.clientHeight,
          uname:'',
          usite:'',
          descb:'',
          showr:false,
          dayAndTime:'',
          uposition:'',
          utelphone:'',
          udiscribe:'',
          ucode:'',
          btntxt:'获取验证码',
          btndisable:true,
          initInfo:[],
          selects:[],
          level:'',
          initid:'',
          d2n:0,
          d3n:0,
          d4n:0,
          d5n:0,
          pickData1: {
            columns: 1, // picker的列数
            default: [
    
              ], // 默认显示哪个
                 // 第一列的数据结构
            pData1: [
    
              ]
}

    


       }
   },
   components: {
      mypicker,
      VuePickers
   },
   computed:{
        
   },
   created(){
      
      this.initInfos1();
   },
   methods: {
    changeAddInfo(index){
       this.showBtn=false;
       var index=index;
       this.getLevelInfo(index);
        
    },

    changeSubInfo(){
       console.log('删除信息');
    },
    getLevelInfo(index){
        
       this.selects.push(this.initInfo[index])
       
       this.level--;
       if(this.level>=0){
          
          console.log(this.selects);
          for(var j=0;j<this.selects.length;j++){
               console.log(j)
          }
          console.log(j);
          console.log(this.selects[j-1]);
          var id=this.selects[j-1].id;
          var initid=this.initid;
          var pid=this.selects[j-1].pid;
          var  self=this;
          this.$http.post('?action=api.post.area',{pid:id,area_id:initid})
          .then(res=>{
           var a=res.data.data;
           this.showBtn=true;
           self.initInfo=[];
           for(let i=0;i<a.length;i++){
               self.initInfo.push(a[i])
           }
           
           console.log(self.initInfo)
            
          })
          .catch(err=>{console.log(err)})
       }else{
           this.showBtn=false;
       }
    },
    InitLevelInfo(){
     
    },
    cancelSelect(){
      this.showStatus();
    },
    confirmSelect(){
       this.showStatus();
       var arr=[];
       console.log(111111111111111);
       console.log(this.selects);
       console.log(111111111111111);
       this.d2n=this.selects[0].id;
       if(this.selects.length==2){
       this.d3n=this.selects[1].id;    
      }
      else if(this.selects.length==3){
        this.d3n=this.selects[1].id; 
        this.d4n=this.selects[2].id;    
       }else if(this.selects.length==4){
        this.d3n=this.selects[1].id; 
        this.d4n=this.selects[2].id; 
        this.d5n=this.selects[3].id;    
       }
       for(let i=0;i<this.selects.length;i++){
           arr.push(this.selects[i].name);
       }
       this.descb=arr.join('');
       
    },
    showStatus(){
      this.showr=false;
      //this.selects=[];
      this.initInfo=[];
     
    },
    getDiscribeInfo(){
       this.showr=true;
    },
    getPositionInfo(){
      this.show1=true;
    },
    confirmFn(data){
        this.show1=false;
       this.selects=[];
       console.log(data);
       this.level=data.select1.value;
       this.uposition=data.select1.text;
       this.initid=data.select1.id;
       this.initInfos(data.select1.id);
    },
    close(){
       this.show1=false;
    },
    initInfos(id){
         var id =id;
         var  self=this;
          this.$http.post('?action=api.post.area',{pid:0,area_id:id})
          .then(res=>{
           var a=res.data.data;
           self.initInfo=[];
           for(let i=0;i<a.length;i++){
               self.initInfo.push(a[i])
           }
           
           console.log(self.initInfo)
            
          })
          .catch(err=>{console.log(err)})
    },
    initInfos1(){
        var  self=this;
        this.$http.post('?action=api.post.describe.list')
        .then(res=>{
           var a=res.data.data;
           var c1=res.data.data[0];
           var c2={};
           c2.text=c1.name;
           c2.value=c1.level;
           c2.id=c1.id;
           self.pickData1.default=c2;
           for(let i=0;i<a.length;i++){
                  var c3={};
                  c3.text=a[i].name;
                  c3.value=a[i].level;
                  c3.id=a[i].id;
            self.pickData1.pData1.push(c3); 
           }
            
        })
        .catch(err=>{console.log(err)})
     },
     
     
     
     
     
      getCode(){
          this.flag1=true;
          var timer;
          var i=60;
          var self=this;
          var ms=/^1[3|4|5|6|7|8|9][0-9]\d{8}$/;
          clearInterval(timer);
          var timer=setInterval(function(){
               if(i>0){
                   i--;
                    self.btntxt=i;
               }
               else{
                   clearInterval(timer);
                   self.btntxt='重新获取'
                   self.flag1=false;
               }
              
          },1000)
          var num=this.utelphone;
          console.log(num);
           if(ms.test(num)){
           this.$http.post('?action=user.post.telcode',{mobile:num})
           .then(res=>{
              if(res.data.code==3){
                  self.$toast({
                      title:'消息提示',
                      content:res.data.msg,
                      
                  })
           
               }
               else{
                self.$toast({
                      title:'消息提示',
                      content:res.data.msg
                  })
              }

           }).catch(err=>{
              console.log(err);
              
          })
         }else{
             self.$toast({
                 title:'消息提示',
                 content: '手机号码输入错误',
             })
         }
      },
      confirmDayTime(){
        console.log(11111);
         
         var self=this;
         var id=this.listinfo[this.pnum-1].id;
         this.$http.post('?action=api.post.area',{pid:id})
        .then(res=>{
          console.log(res)
           self.listinfo=res.data.data;
           let a=res.data.data;
              self.fchoose1[0].values=['请选择',];
          for(let i=0;i<a.length;i++){
             self.fchoose1[0].values.push(a[i].name)
          }
          self.selectsf1=true;
        })
        .catch(err=>{console.log(err)})
      },
     goNextTip(){
         var uid=localStorage.getItem('uid');
        var a={
            name:this.uname,
            tb_user_id:uid,
            describe:this.uposition,
            contact_moblie:this.utelphone,
            code:this.ucode,
            d2n:this.d2n,
            d3n:this.d3n,
            d4n:this.d4n,
            d5n:this.d5n
        }
        var b=JSON.stringify(a);
        localStorage.setItem('rinfo',b)
         for(var i=0;i<document.form1.elements.length-1;i++)
        {
         if(document.form1.elements[i].value=="")
         {
           
           document.form1.elements[i].focus();
           return false;
         }
        }
      
        this.$router.push({
            name:'uploadcard'
        })
     }
     
      
   },
   watch:{
       findBtnStatus(){
           
       }
   }
}
</script>



<style>
.nidentity-container{
    width:100%;
    background:#f5f5f5;
}
.weui-cell{
    height:80px !important;
    font-size:16px !important;
    padding:0 30px !important;
}
.warntit{
    padding:10px 20px;
    display: inline-block;
}
.weui-cell__ft{
  
}
.weui-vcode-btn{
    border:none !important;
    background:#ff9900 !important;
    color:#fff !important;
    width:140px !important;
 }
 .msnInfo{
   padding:0 5%;
   margin-top:80px;
 }
 .next-click{
    height:90px;
    width:100%;
    line-height: 90px;
    text-align: center;
    border:none;
 }
 .a-btn{
   background:#ff9900;
 }
 .b-btn{
     background:#bdbdbd;
     pointer-events: none;
 }
</style>


