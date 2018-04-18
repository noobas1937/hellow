<template>
<div>
  <div class="foods" v-show="a" v-for='(item,index) in itemDate' :key="index" @click="setFood(index)">
    
    <div class="fs-1">
        <img :src="imgurl" alt="" class="fimg">
    </div>
    <div class="fs-2">
        <h5>{{item.name}}</h5>
        <span class="fs-3">任意组合，只选你喜欢的</span>
         <div class="fs-4">
             <span>￥<b>17</b>/1天</span>
             <span class="fs-5">{{item.start_time}}-{{item.end_time}}(7天)</span>
         </div>
    </div>
    </div>
    <div v-show="b" class="fs-6">
        <span class="fs-9"></span>
        <div class="fs-7">
            <img src="../assets/img/noitem.png" alt="">
        </div>
        <span class="fs-8">本周预定时间已过，下周一再来查看最新套餐哦。</span>
    </div>
  </div>
</template>

<script>
export default {
     data(){
         return{
            a:true,
            b:false,
            itemDate:[],
            imgurl:''
         }
     },
     beforeCreate(){
       var self=this
       this.$http.post('api/index/index').then(function(response) {
        console.log(response.data.data) 
        self.itemDate=response.data.data;
        self.imgurl=response.data.data[0].package_image
        console.log(self.imgUrl)
        console.log(self.itemDate)
        }).catch(error=>{
       console.log(error)
       })
     },
     created(){
        this.pdItem()
     },
     methods:{
         pdItem(){
         var s=new Date();
         var week=s.getDay();
         var hh=s.getHours();
         var self=this;
         console.log(week);
         console.log(hh);
         if(week==0||week>=5){
             if(hh>12){
              console.log('无商品了')
               self.a=false,
               self.b=true
             }
             else{
               self.b=false,
               self.a=true
             }
         }
         else{
               self.b=false,
               self.a=true
         }
        },
        setFood(index){
            var foodid=this.itemDate[index].Id;
            console.log(foodid)
           this.$router.push({ name: 'Uorder', params: { foodid: foodid }})
        }
     }
}
</script>

<style>
 .foods{
    width:100%;
    height:192px;
    padding:34px 0 34px 34px;
    display: flex;
    flex-direction: row;
    -moz-flex-direction: row; 
    border-bottom:solid 1px #dcdcdc; 
    overflow: hidden;
    background:#fff;
 }
 .fimg{
     width:184px;
     height:184px;
 }
 .fs-1{
    
 }
 .fs-2{
    margin-left:20px;
    width:60%;
    display: flex;
    flex-direction: column; 
    -moz-flex-direction: column; 
 }
 .fs-2 h5{
     font-size: 30px;
     text-align: left;
     color:#4b4b4b;
  }
  .fs-3{
      margin-top:20px;
    font-size: 22px;
    text-align: left;
    color:#707070;
  }
  .fs-4{
  font-size: 24px;
   margin-top:28px;
   text-align: left;
  }
  .fs-4 b{
   font-size: 46px;
   color:red;
  }
.fs-5{
    margin-left:10%;
}
.fs-6{
    width:100%;
    height: 460px;

}
.fs-7{
    width:200px;
    height:200px;
    background:pink;
    margin:0 auto;
}
.fs-7 img{
    width:100%;
    height:100%;
}
.fs-8{
    font-size:28px;
    display:block;
    margin-top:30px;
}
.fs-9{
    display:block;
    margin-top:80px;
}
</style>


