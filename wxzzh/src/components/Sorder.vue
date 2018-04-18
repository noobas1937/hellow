<template>
<div class="listContainer">
 <div class="orderlist" @click="dropDown(index)" v-for="(item,index) of itemData" :key="index">
     <div  class="orderlist" :class="{'active1':ind===index}">
       <div class="olistt">
        <span class="os-1">{{item.weekday}}({{item.formate_date}})</span>
        <span @click='cancelitem(index)' class="cci">{{picks[index]=='Z'?'已取消':'取消'}}</span>
        <span class="os-3">换个口味</span>
        <span v-show="ind!=index"  class="os-4">
            <img src="../assets/img/top.png" alt="">
        </span>
        <span v-show="ind===index" class="os-4">
             <img src="../assets/img/bottom.png" alt="">
        </span> 
        </div>
        <div class="olist olist1" :class="ind==index?'active':'uactive'">
          <div class="io-1" v-for="(item1,index1) of item.dishes_type" :key="index1">
           <input type="radio" id="index1" :value="item1.lable" v-model="picks[index]" class="io-5">
              <label :for="item"  class="io-2">
                   <img :src="item1.image" alt="">
                   <span  class="io-3">{{item1.name}}</span>
                   <span class="io-4">￥{{item1.price}}</span>
             </label>
             <br>
            </div> 
        </div>       
     </div>
 </div>
   <div class="msorder" @click='orderInfo'>确认</div>

   <div class="zcontainers" v-show="active4">
    <div class="zt-1"></div>
     <div class="zt-2c">
      <span class="zt-3c">{{msegs}}</span>
      <span class="zt-4c" @click="msssure()">好的</span>
      <div class='zt-rm' @click="cancelss()">X</div>
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
       otitle:'西红柿炒番茄盖烧饭+统一冰红茶',
       uname:'张三',
       ititle:'西红柿番茄盖烧饭+统一冰红茶饮品',
       status:'代领',
       price:17,
       itemData:[],
       picked:'',
       picked1:'',
       picked2:'',
       picked3:'',
       picked4:'',
       picked5:'',
       picked6:'',
       picked7:'',
       flag:'',
       picks:[],
       a:true,
       b:false,
       ins:'',
       ccitem:[],
       active4:false,
       msegs:'',
       inn:[0,0,0,0,0,0,0]
    }
      },
    created(){
      this.selectFood();
     
    },
    methods: {
      dropDown(index){
           this.flag=index;
          // console.log(this.picks)
         //  console.log(index)
           this.open1=!this.open1;
           this.open2=!this.open2;
           this.ind=index;
           this.iny=!index;
         
      },
     cancelitem(index){
        //console.log(index);
        
        if(this.picks[index]='Z'){
           this.picks[index]='Z'  
        }
        else{
           this.picks[index]=this.picks[index];
        }
        
     },
     selectFood(){
         var foodid=this.$route.params.foodid;
         //console.log(foodid);
         var self=this;
         this.$http.get('api/index/package_detail?id='+foodid).then(response=>{
             console.log(response.data)
             self.itemData=response.data.data;
             console.log(self.itemData)
             for(var i=0;i<response.data.data.length;i++){
                 var label=response.data.data[i].dishes_type.A.lable;
                     self.picks.push(label)
                     console.log(self.picks)
                
             }
         }).catch(error=>{
             console.log(error)
         })
     },
     updateDate(){
       
     },
     orderInfo(){
         this.ccitem=[];
         var date=[];
         var itemData=this.itemData;
         var uid=localStorage.getItem('uId');
         for(var i=0;i<itemData.length;i++){
             
         }
         var picks=this.picks;
         var dates=this.itemData;
         //console.log(dates);
          //console.log('测试那个取消长度');
         for(var i=0;i<picks.length;i++){
           if(picks[i]=='Z'){
                 this.picks[i]="Z"+'-'+dates[i].date;
                 this.ccitem.push(picks[i]);
                 //this.inn.push(picks[i])
             }
             else{
                //this.inn.push('0') 
             }
         }
         //console.log(this.inn);
        
        // console.log(picks);
    if(this.ccitem.length<3){
           this.$http.post('api/order/reserve',{userid:uid,datas:this.picks} 
           ).then(response=>{
           // console.log(response.data)
                if(response.data.status=='failer'){
                  this.msegs=response.data.mesg;
                  this.active4=true;
               }
           else{
                   this.$router.push({ name: 'Seorder'})
                }
            })
              .catch(error=>{console.log(error)})
             }
        else{
             this.active4=true;
             this.msegs='每周最多只能取消两单！'; 
             }    
    
     },
    msssure(){
     this.active4=false;
    },
    cancelss(){
     this.active4=false;
    },
   
    
},

  }
</script>
<style>
 .orderlist{
     width:100%;
     background:#fff;
     text-align:left;
     font-size:28px;
 }
 .io-1{
    display:flex;
    flex-direction: row; 
    overflow:hidden;
    border-bottom:solid 1px #f5f5f5;
    padding:20px 30px;
 }
 .io-5{
   margin:30px 0;
 }
 .io-2{
     width:100%;
     display:flex;
    flex-direction: row;
   margin:0 28px;
 }
 .io-2 img{
     width:90px;
     height:90px;
 }
 .io-3{
    width:60%;
    margin:0 0 0 40px;
    padding-top:20px;
 }
 .io-4{
    margin:20px 0 0 20%;
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
     display: none;
 }
 ul{
     margin:0;
     padding:0;
 }
 li{
     list-style:none;
 }
 .olistt{
     width:100%;
     height:80px;
     line-height:80px;
     text-align:left;
     color:#000;
     padding:30px;
     border-bottom:solid 1px #dcdcdc;
     display: flex;
     flex-direction: row;
 }
 .listContainer{
     background:#f5f5f5;
     overflow:hidden;
 }
 .os-1{
     display:block;
     width:25%;
     
 }
 .cci{
    display:block;
     width:20%; 
 }
 .os-3{
     display:block;
     width:42%;
     text-align:right;
    
     
 }
 .os-4 img{ 
     width:32px;
     height:32px;
     margin:0px 0 0 20px;
 }
 .active1{
     margin:20px 0 20px 0; 
     }
 
 
  .olist1{
      background:#fff;
      padding:15px 0;
      overflow: hidden;
      
  }
  .olist2{
      padding:30px;
      overflow: hidden;
  } 
  .msorder{
    height:100px;
    width:100%;
    background: #f39d0a;
    text-align: center;
    line-height: 100px;
    font-size:34px;
    color:#fff; 

  }
  
</style>
