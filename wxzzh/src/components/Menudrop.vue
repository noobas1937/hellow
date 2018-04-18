<template>
<div class="listContainer">
 <div class="orderlist" @click="dropDown(index)" v-for="(item,index) of siteorderdata" :key="index">
     <div  class="orderlist" :class="{'active1':ind===index}">
       <div class="olistt">
        <span class="os-11">光谷站点</span>
        <span class="os-21">{{item.weekday}}({{item.day}})</span>
        <span class="os-31">共<b>{{item.total}}</b>份(已领{{item.received}}份)</span>
        <span v-show="ind!=index"  class="os-41">
            <img src="../assets/img/top.png" alt="">
        </span>
        <span v-show="ind===index" class="os-41">
             <img src="../assets/img/bottom.png" alt="">
        </span> 
        </div>
        <ul class="olist olist1" :class="ind==index?'active':'uactive'" v-show="open1">
            <li class="ot-1" v-for="(item1,index1) of item.dishescount" :key="index1">
                 <span class="ot-2"><img src="../assets/img/tfood.png" alt=""></span>
                 <span class="ot-3">{{item1.name}}</span>
                 <span class="ot-4">x{{item1.number}}份</span>
            </li>
            
        </ul>
        <ul class="olist olist2" :class="ind==index?'active':'uactive'"  v-show="open1">
        <li class="ot-8" v-for="item2 in item.list" :key="item2">
            <span class="ot-5">{{item2.name}}</span>
            <span class="ot-6">{{item2.cname}}</span>
            <span class="ot-7">{{item2.status}}</span>
        </li> 
        </ul>        
     </div>
 </div>
 
</div>
</template>
<script>
  export default {
  data(){
    return{
       open1:true,
       open2:false,
       ind:'',
       iny:'',
       siteorderdata:[]
    }
      },
    created(){
       this.siteOrder();
    },
    methods: {
      dropDown(index){
         // console.log(index)
           this.open1=!this.open1;
           this.open2=!this.open2;
           this.ind=index;
           this.iny=!index;
           //console.log(this.iny)
      },
      siteOrder(){
          var self=this;
          var siteid=localStorage.getItem('usiteid')
          this.$http.get('api/order/siteorder?siteid='+siteid)
          .then(response=>{console.log(response.data);self.siteorderdata=response.data.data})
          .catch(error=>{console.log(error)})
      }
    }
  }
</script>
<style>
 .orderlist{
     width:100%;
     background:#fff;
     text-align:left;
 }
 .olist{
    width:100%; 
 }
 .olist img{
   width:80px;
   height:80px;
 }
 .active{
     display:block;
 }
 .uactive{
     display:none;
 }
 ul{
     margin:0;
     padding:0;
 }
 li{
     list-style:none;
 }
 .olistt{
     font-size:28px;
     width:100%;
     height:80px;
     line-height:80px;
     text-align:left;
     color:#000;
     padding:30px;
 }
 .listContainer{
     background:#f5f5f5;
     overflow:hidden;
 }
 .os-11{ 
    
     font-size:28px;
 }
 .os-21{
     font-size:28px;
     margin-left:30px;
  }
 .os-31{ 
     margin-left:22%;
 }
 .os-31 b{
   color:#ff8256;
 }
 .os-41{
     padding-top:10px;
 }
 .os-41 img{ 
     width:32px;
     height:32px;
     margin:0px 0 0 20px;
 }
 .active1{
     margin:20px 0 20px 0; 
     }
 .ot-1{
     width:100%;
     height:90px;
     display:flex;
     flex-direction:row;
      padding:0 30px;
     }
 .ot-2{

  } 
  .ot-3{
      display:block;
      width:50%;
      padding:20px 0 0 19%;
  }
  .ot-4{
     padding:20px 0 0 10%;   
  } 
  .olist1{
      background:#fff6e9;
      padding:15px 0;
      overflow: hidden;
  }
  .olist2{
      padding:30px;
      overflow: hidden;
  } 
  .ot-8{
      width:100%;
      margin:10px 0;
     display:flex;
     flex-direction:row;
  } 
  .ot-5{
      display:block;
      width:120px;
  }
  .ot-6{
      display:block;
      width:468px;
      margin-left:52px;
  }
  
</style>
