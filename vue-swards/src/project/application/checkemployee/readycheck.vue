<template>
	<div class="ep-box">
	<div class="employee-list2">	
      <p class="ep-name">{{bank_name}}</p>
      <div class="epl-1-4 cr-11 ep-state" v-if="status==1">待审核</div>
      <div class="epl-1-4 cr-13 ep-state ep-state1" v-else-if="status==2">已通过</div>
      <div class="epl-1-4 cr-6  ep-state ep-state2" v-else-if="status==3">未通过</div>
<!--      <div class="epl-1-4 cr-11 ep-state ep-state3" v-if="this.$route.params.status==1">站长已审核</div>-->
    </div> 
    
    
    <div class="detail">
       <div class="detail-cal">
       	 <p class="detail-call ">所属站点</p>
       	 <p class="detail-message ">{{d3}} {{d4}} {{d5}}</p>
       </div>
       
       <div>
       	 <p class="detail-call">职务</p>
       	 <p class="detail-message">{{describe}}</p>
       </div>
       
       <div>
       	 <p class="detail-call">手机号</p>
       	 <p class="detail-message">{{contact_moblie}}</p>
       </div>
       
       <div>
       	 <p class="detail-call">身份证号</p>
       	 <p class="detail-message">{{idcard}}</p>
       </div>
       
      <div>
       	 <p class="detail-call">证件照片</p>
       	 <p class="detail-message detail-message2">
       	  <img :src="idcardImgPositive">
          <img :src=" idcardImgOpposite">
         </p>
       </div>

       <div>
       	 <p class="detail-call">手持证件拍照</p>
       	 <p class="detail-message detail-message3">
       	  	<img :src="idcardImgHold">
       	 </p> 	
       </div>
      </div>
     
       <div class="result" >
       	<div class="result-n" @click="resulty(1)">不通过</div>
       	<div class="result-y" @click="resulty(2)">审核通过</div>
       </div> 
       
       <!--<div class=" dimission " v-if="this.$route.params.status==2">
       	 设为离职
       </div>
       -->
    </div>
</template>

<script>
import checkemp from '../../../store/checkemployee'
export default {
    data(){
        return{
        	 status:{},
             contact_moblie:{},
             describe:{},
             idcard:{},
             idcardImgHold:{},
             idcardImgOpposite:{},
             idcardImgPositive:{},
             bank_name:{},
             d3:{},
             d4:{},
             d5:{},
        }
    },created(){
       this.getMyRewardsInfo();
     },
     methods:{
       getMyRewardsInfo(){
       	this.status = checkemp.state.status
       	var self=this;
       	 var eid=localStorage.getItem('eid');
       	 var uid= checkemp.state.uid
          this.$http.post('?action=employee.apply.applyinfo',{uid:uid})
          .then(res=>{
              console.log(res.data);
              var resd = res.data.data
              self.contact_moblie = resd.contact_moblie
              self.describe = resd.describe
              self.idcard = resd.idcard
              self.idcardImgHold = resd.idcardImgHold
              self.idcardImgOpposite = resd.idcardImgOpposite
              self.idcardImgPositive = resd.idcardImgPositive
              self.bank_name = resd.name
              self.d3 = resd.d3
              self.d4 = resd.d4
              self.d5 = resd.d5
          }).catch(error=>{
              console.log(error);
          })
       },
       resulty(a){
       	 var uid= checkemp.state.uid
       	    if(a=='1'){
			        this.$router.push({
			          name:'checkfail',
			          params: { 
			               uid:uid,
			               name:this.bank_name
			            }
			        })
              }
       	   this.$http.post('?action=employee.apply.check',{apply_id:uid,apply_status:a,uid:2,apply_remark:"" })
          .then(res=>{
              console.log(res.data);
              if(a=='2'){
              	   this.$toast({
              	   	 title:'审核信息',
              	   	 content:'审核已通过'
              	   })
              	  this.setTimer()  
               }
          
          }).catch(error=>{
              console.log(error);
          })
         },
        setTimer () {
        clearTimeout(this.timer)
        this.timer = setTimeout(() => {
           this.$router.push({
		      name:'useradmin'
		      
		    })
        }, 2000)
        }
     }
}
</script>


<style>
	html{
		background-color: #fff;
	}
	.ep-box{
		margin: 20px;
	}
.employee-list2{
   width:100%;
   margin-top: 50px;
 }
.employee-list2 .ep-img{
	border-radius: 50%;
	display: block;
	margin: 0 auto;
	width: 20%;
	height: 20%;
	margin-top:60px;
} 
.employee-list2 .ep-name{
	color: #000;
	text-align: center;
	font-size: 30px;
	font-weight: 600;
	margin: 5px 0 10px 0;
	letter-spacing: 5px;
}
.employee-list2 .ep-state{
	border: 1px solid #ffa800;
	width: 12%;
	text-align: center;
	border-radius: 3px;
	margin: 0 auto;
	font-size: 10px;
}
.employee-list2 .ep-state1{
	border: 1px solid #00ff00;
}
.employee-list2 .ep-state2{
	border: 1px solid #999999;
}
.detail{
	margin-top: 70px;
	border-top: 0.5px solid #e8e8e8;
}
.detail div{
	width: 100%;
	margin: 5px 0;
	height: 45px;
	line-height: 45px;
}
.detail-call{
	width: 25%;
	float: left;
	color: #000;
	font-weight: 600;
	font-size: 26px;
}
.detail-message{
	width: 75%;
	float: left;
	color: #d0d0d0;
	font-size: 26px;
}
.detail-cal{
	padding-top:15px;
}
.detail-message2 img{
	width: 35%;
	margin: 10px 5% 0 0;
	border-radius: 5px;
    box-shadow: darkgrey 5px 5px 15px 5px ;
}
.detail-message3 img{
	width: 77%;
	margin-top: 15px;
	border-radius: 10px;
	box-shadow: darkgrey 5px 5px 15px 5px ;
}
.result{
	width: 100%;
	margin-top: 60px;
	float: left;
}
.result div{
	height: 80px;
	line-height: 80px;
	float: left;
	border-radius: 8px;
	text-align: center;
	color: #fff;
	font-size: 30px;	
}
.result .result-n{
	background-color: #999999;
	width: 45%;
	letter-spacing: 3px;
}
.result .result-y{
	background-color: #f39838;
	width: 45%;
	letter-spacing: 3px;
	margin-left:3%;
}
.dimission{
	width: 90%;
	background-color: #999;
	height: 80px;
	float: left;
	text-align: center;
	line-height: 80px;
	margin-left: 5%;
	color: #fff;
	border-radius: 5px;
	margin-top: 40px;
	font-size: 30px;
	letter-spacing: 3px;
}
.employee-list2 .ep-state3{
	width: 30%;
}
</style>


