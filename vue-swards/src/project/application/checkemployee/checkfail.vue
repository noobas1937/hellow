<template>
    <div class="ep-box">
	<div class="employee-list2">	
      <p class="ep-name">{{this.$route.params.name}}</p>
      <div class="epl-1-4 cr-11 ep-state">待审核</div>
    </div> 
    <p class="cause-tit">请选择审核不通过的原因</p>
    <div class="cscs">
    	<span>员工信息填写有误</span>
        <input id="item1" type="radio"  v-model="inputdata" name="item" value="1" checked>
        <label for="item1"></label>
    </div>
    <div class="cscs">
    	<span>手持身份证拍照不清晰、号码无法识别</span>
        <input id="item2" type="radio" v-model="inputdata" name="item" value="2">
        <label for="item2"></label>
    </div>
    
    
    <div class="tijiao" v-if="inputdata!=1&&inputdata!=2">提交</div>
    <div class="tijiao tijiao1" v-if="inputdata==1||inputdata==2" @click="subm">提交</div>
    </div>
</template>


<script>
	import checkemp from '../../../store/checkemployee'
export default {
    data(){
        return{
              inputdata:""
        }
    },
    methods:{
    subm(){
       var apply_id =  checkemp.state.uid
       this.$http.post('?action=employee.apply.check',{apply_id:apply_id,apply_status:2,uid:2,apply_remark:"员工信息填写有误"})
          .then(res=>{
              console.log(res.data);
              	   this.$toast({
              	   	 title:'审核信息',
              	   	 content:'待审核原因已提交'
              	   })
              	  this.setTimer()  
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
 .ep-box{
		margin: 20px;
	}
.employee-list2{
   width:100%;
   margin-top: 50px;
 }
 .cause{
 	margin-top: 60px;
 }
 .cause-tit{
 	color: #000;
 	font-size: 26px;
 	font-weight: 600;
 	margin-bottom: 20px;
 	margin-top: 40px;
 }
 .cause label{
 	width: 100%;
 	float: left;
 	display: block;
 	border-bottom: 1px solid #ededed;
 	height: 90px;
 	line-height: 90px;
 	font-size: 26px;
 }
.tijiao{
	width: 90%;
	height: 80px;
	margin: 0 2.5%;
	color: #fff;
	background-color: #999;
	border-radius: 10px;
	border: none;
	font-size: 30px;
	margin-top: 90px;
	float: left;
	text-align: center;
	line-height: 80px;
}
.tijiao1{
	background-color: #00ff00;
}
      .cscs{
            position: relative;
            line-height: 80px;
            border-bottom: 1px solid #ededed;
            font-size: 24px;
            height: 80px;
        }
        
        input[type="radio"] {
            width: 40px;
            height: 40px;
            opacity: 0;
        }
        label {
            position: absolute;
            right: 5px;
            top: 15px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 1px solid #999;
        }
        input:checked+label { 
            background-color: #fe6d32;
            border: 1px solid #fe6d32;
        }
        input:checked+label::after {
            position: absolute;
            content: "";
            width: 15px;
            height: 30px;
            top: 6px;
            left: 15px;
            border: 2px solid #fff;
            border-top: none;
            border-left: none;
            transform: rotate(45deg)
        }

</style>


