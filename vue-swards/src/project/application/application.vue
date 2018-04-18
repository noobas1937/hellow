<template>
 <div class="myn bg-8">
  
  <div class="myn-1 column">
     <div class="myn-1a cr-1 row" @click="goPersonInfo" :style="bgs.background">
          <img :src="wxinfo?wxinfo.avatar:''" alt=""  class="myn-1a-imgss">
          
          

          <div  class="myn-1a-1 column" v-if="name">
            <div  class="myn-1a-1a row">
              <span class="fs-3 myn-name">{{name}}</span>
              <span class="fs-1 myn-tel">{{mobile}}</span>
            </div>
            <div  class="myn-1a-1b fs-1 row">
              <span class="myn-site">{{site?site:''}}</span>
              <span class="myn-position">{{des}}</span>
            </div>
          </div>

          <div  class="myn-1a-1 column" v-else>
            <div  class="myn-1a-1a row">
              <span class="fs-3 myn-name">{{wxinfo.nickname}}</span>
              <span class="fs-1 myn-tel" v-if="mseid<0">审核中</span>
              <span class="fs-1 myn-tel" v-else-if="mseid==0">未认证</span>
            </div>
          </div>

     </div>

    <div class="bg-1">
     <div class="myn-1b-1" @click="employeeManage" v-show="true">
          <img src="../../assets/img/6m.png" alt=""  class="myn-1b-img lef">
          <span class="cr-2 fs-1 lef myn-1b-1a">员工管理</span>
          <img src="../../assets/img/rig.png" alt="" class="myn-1b-rig rig">
     </div>

     
    </div>

     <div class="myn-1b bg-1 column">

       <div class="myn-1b-1"  @click="goMyIntegration">
          <img src="../../assets/img/1m.png" alt=""  class="myn-1b-img lef">
          <span class="cr-2 fs-1 lef myn-1b-1a">我的奋斗金</span>
          <span class="cr-11 fs-0 lef myn-1b-1b">{{appinfo.point}}</span>
          <img src="../../assets/img/rig.png" alt="" class="myn-1b-rig rig">
          <span class="cr-6 fs-1 rig">兑换提现</span>
       </div>

       <div class="myn-1b-1" @click="goMyWages">
          <img src="../../assets/img/2m.png" alt=""  class="myn-1b-img lef">
          <span class="cr-2 fs-1 lef myn-1b-1a">我的工资</span>
          <img src="../../assets/img/rig.png" alt="" class="myn-1b-rig rig">
       </div>
       <div class="myn-1b-1" @click="goMyReward">
          <img src="../../assets/img/3m.png" alt=""  class="myn-1b-img lef">
          <span class="cr-2 fs-1 lef myn-1b-1a">我的奖励</span>
          <img src="../../assets/img/rig.png" alt="" class="myn-1b-rig rig">
       </div>
     </div>

  </div>

  <div class="myn-2 bg-1">
      <div class="myn-1b-1" @click="goMyRewards">
          <img src="../../assets/img/4m.png" alt=""  class="myn-1b-img lef">
          <span class="cr-2 fs-1 lef myn-1b-1a">我的夺宝</span>
          <img src="../../assets/img/rig.png" alt="" class="myn-1b-rig rig">
      </div>

      <div class="myn-1b-1" v-show="false">
          <img src="../../assets/img/5m.png" alt=""  class="myn-1b-img lef">
          <span class="cr-2 fs-1 lef myn-1b-1a">我的抽奖</span>
          <img src="../../assets/img/rig.png" alt="" class="myn-1b-rig rig">
          <span class="cr-6 fs-1 rig">中奖记录</span>
      </div>
  </div>

  <div class="myn-3 bg-1">
     <div class="myn-1b-1" @click="goMyProblem">
          <img src="../../assets/img/6m.png" alt=""  class="myn-1b-img lef">
          <span class="cr-2 fs-1 lef myn-1b-1a">常见问题</span>
          <img src="../../assets/img/rig.png" alt="" class="myn-1b-rig rig">
      </div>

      <div class="myn-1b-1" v-show="false">
          <img src="../../assets/img/7m.png" alt=""  class="myn-1b-img lef">
          <span class="cr-2 fs-1 lef myn-1b-1a">联系客服</span>
          <img src="../../assets/img/rig.png" alt="" class="myn-1b-rig rig">
      </div>

       <div class="myn-1b-1" v-show="false" @click="goNewIdentity">
          <img src="../../assets/img/7m.png" alt=""  class="myn-1b-img lef">
          <span class="cr-2 fs-1 lef myn-1b-1a">新认证测试</span>
          <img src="../../assets/img/rig.png" alt="" class="myn-1b-rig rig">
      </div>

  </div>

  <tabbar></tabbar>
 </div>
 
</template>

<script>
import tabbar from'@/components/tabbar'

export default {
   data(){
       return{
           point: "",
           mobile: "",
           name: "",
           site: "",
           des:  "",
           mseid:'',
           appinfo:{},
           wxinfo:{},
           bgs:{ background:"background-image:"+"url(" + require("../../assets/img/aapp.jpg") + ");"}
       }
   },
    components:{
        tabbar,
    },
   created(){
     // this.getApplicationInfo();
      this.goEmployeeInfo();
      this.getCreditInfo();
   },
   
   methods:{
       getApplicationInfo(){
          var self=this;
          var eid=localStorage.getItem('eid');
          
          this.$http.post('?action=lucky.get.luckyapplyinfo',{user_id:eid,draw_id:2}).then(res=>{
              console.log(res) 
          }).catch(err=>{
              console.log(err)
          })
       },
       goNewIdentity(){
          this.$router.push({
             name:'nidentity'
          })
        },
       getCreditInfo(){
          var self=this;
          var eid=localStorage.getItem('eid');
          this.$http.get('?action=user.get.usercenter&user_id='+eid).then(res=>{
              console.log(res) 
              if(res.data.status=='success'){
                self.appinfo=res.data.data;
              }
          }).catch(err=>{
              console.log(err)
          })
       },
       goEmployeeInfo(){
          var self=this;
          var eid=localStorage.getItem('eid');
          this.mseid=localStorage.getItem('eid');
          var mobile=localStorage.getItem('mobile');
          this.$http.post('?action=user.get.employeeinfo',{user_id:eid}).then(res=>{
              console.log(res) 
               if(res.data.status=='success'){
                    self.point= res.data.data.points;
                    self.mobile= res.data.data.contact_moblie;
                    self.name= res.data.data.name;
                    self.site= res.data.data.site;
                    self.des= res.data.data.describe;
                    self.wxinfo=res.data.data.wechatuser;
               }
          }).catch(err=>{
              console.log(err)
          })
       },
       goMyIntegration(){
         this.$router.push({
          name:'myintegration',
          params: { 
               
            }
        })
       },
       goMyWages(){
         this.$router.push({
          name:'mywages',
          params: { 
               
            }
        })
       },
       goMyReward(){
         this.$router.push({
          name:'myreward',
          params: { 
              
            }
        })
       },
       goPersonInfo(){
         this.$router.push({
          name:'personinfo',
          params: { 
                
            }
        })
       },
      goMyProblem(){
        this.$router.push({
          name:'myproblem',
          params: { 
                
            }
        })
      },
      goMyRewards(){
         this.$router.push({
          name:'myswards',
          params: { 
                
            }
        })
       },
      employeeManage(){
        this.$router.push({
          name:'useradmin',
          params: { 
                
            }
        })
      },
      toMySwards(){

      },
      toMySwards1(){

      },
      usuProblem(){

      }
   }
}
</script>



<style>
 html{
   background: #f5f5f5;
   }

.myn{
  width:100%;
}
.myn-1{
  width:100%;
 
}
.myn-1a{
  width:100%;
  height:200px;
  background-repeat:no-repeat;
  background-position:center;
  background-size: cover;
 
}
.myn-1a-1{
 
}
.myn-1a-1a,.myn-1a-1b{
 
}
.myn-1a-1b{
  margin:0 0 0 20px;
}
.myn-1a-1a{
  margin:50px 0 0 20px;
}
.myn-1a-imgss{
  width:120px;
  height:120px;
 
  border-radius: 50%;
  border:2px solid  red;
  margin:40px 0 0 36px;
}
.myn-tel{
  line-height:54px;
  margin-left:18px;
}
.myn-position{
  margin-left:18px;
  line-height: 40px;
}
.myn-1b{
 
 
}
.myn-1b-1{
  line-height:100px;
  height:100px;
  padding:0 30px;
  border-bottom:0.5px solid #bdbdbd;
}
.myn-1b-img{
  width:46px;
  height:46px;
  margin-top:26px;
}
.myn-1b-rig{
  width:36px;
  height:36px;
  margin-top:30px;
}
.myn-1b-1a{
  margin-left: 35px;
}
.myn-1b-1b{
  margin-left: 12px;
}
.myn-2{
  margin-top:20px;
}

.myn-3{
  margin-top:20px;
}
</style>


