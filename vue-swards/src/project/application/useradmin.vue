<template>
   <div class="employee-list bg-8" :style="'height:'+newheight+'px;'">
     <div class="epl-1 fs-m bg-8 cr-6 row">
         <span class="epl-1-1">姓名</span>
         <span class="epl-1-2">电话</span>
         <span class="epl-1-3">职务</span>
         <span class="epl-1-4">状态</span>
     </div>
     
     
     <div class="epl-1 bg-1 fs-m cr-2 fw bb row" v-for="(item,index) in epinfo" :key="index" @click="sendParams(item.tb_user_id)">
         <span class="epl-1-1">{{item.name}}</span>
         <span class="epl-1-2">{{item.contact_moblie}}</span>
         <span class="epl-1-3">{{item.describe}}</span>
         <span class="epl-1-4 cr-11">待审核</span>
         <!--<span class="epl-1-4 cr-11" v-if="item.status==1">待审核</span>
         <span class="epl-1-4 cr-13" v-else-if="item.status==2">已通过</span>
         <span class="epl-1-4 cr-6" v-else-if="item.status==3">未通过</span>-->
     </div>
     
     <div class="empty" v-if="epinfo==''">
                恭喜你，所有人员已审核通过！
     </div>
     
   </div>
</template>

<script>
	import checkemp from '../../store/checkemployee'
export default {
    data(){
        return{
            newheight:document.documentElement.clientHeight,
            epinfo:[]
        }
    },
    created(){
       this.getMyRewardsInfo();
    },
     methods:{
       getMyRewardsInfo(){
       	 var eid=localStorage.getItem('eid');
          this.$http.post('?action=employee.apply.applylist',{user_id:2})
          .then(res=>{
          	this.epinfo = res.data.data.list
            console.log(res.data.data);
          }).catch(error=>{
              console.log(error);
          })
        },       
     sendParams(st){
     checkemp.state.status = 1
     checkemp.state.uid = st 
        this.$router.push({
          name:'readycheck',
          params: { 
               uid:st
            }
        })
       }
     }
}
</script>

<style>
 .employee-list{
   width:100%;
 }
 .epl-1{     
     height:90px;
     line-height:90px;
 }
 .epl-1-1{
     width:20%;
     text-align: center;
 }
 .epl-1-2{
     width:40%;
     text-align: left;
 }
 .epl-1-3{
     width:20%;
     text-align: left;
 }
 .epl-1-4{
     width:20%;
     text-align: left;
 }
 .bb{
     border-bottom:1px solid #f5f5f5;
 }
 .empty{
 	background-color: #fff;
 	text-indent:0.7rem;
 	font-size: 26px;
 	font-weight: 600;
 	height: 1rem;
 	line-height: 1rem;
 }
</style>


