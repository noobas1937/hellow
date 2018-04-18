<template>
<div>
<div class='calender'>
  <div class="hr-1">
      <span >{{datashows}}</span>
      <span class="hr-2">已领取餐品<b class="hr-num">{{orderdata.received}}</b>份</span>
      <span class="hr-3" @click="findotherorder()">
          <img src="../assets/img/calender.png" alt=""> 
      </span>
  </div>
  <div class="weeks">
     <ul>
         <li v-for="(item,index,key) in weeks" v-bind:key="item">{{item}}</li>
     </ul>
  </div>
  <div class="days">
      <ul>
         <li v-for="(item1,index1) in space1" :key="item1" class="hr-5"></li>
         <li v-for="(item,index) in date.dt" :key="index" :class="[iny[item]==index+1?'hr-4':'hr-6',inz==index?'hr-7':'',dd==index+1?'hr-8':'']" @click="changebg(index)">{{item}}</li>
         <li v-for="(item2,index2) in space2" :key="item2" class="hr-5"></li>
     </ul>
  </div>
  <div class="hr-1" @click="showAll()">
      <span class="hr-2">待领餐品<b class="hr-num">{{orderdata.unreceived}}</b>份</span>
  </div>
</div>
<div class='iteminfo' >
  <ul v-show='active3'>
      <li v-for="(item,index,key) of orderdata.list" v-bind:key="index">
          <span class='im-1'>
              <span>{{item.monthday}}</span>
              <span>{{item.weekday}}</span>
          </span>
          <span class='im-2'>
              <img :src="item.image" alt="">
          </span>
          <span class='im-3'>
            {{item.cname}}
          </span>
          <span class='im-5' >
              <button @click='showUpdate(item.date)' :disabled='item.editable==0' :class="item.editable==0?'a11':'b11'">修改</button>
          </span>
          <span class='im-4'>{{item.status}}</span>
      </li>
  </ul>
  
 <ul v-show='active4'>
      <li>
          <span class='im-1'>
              <span>{{detailData.date1}}</span>
              <span>{{detailData.weekday}}</span>
          </span>
          <span class='im-2'>
              <img :src="detailData.image" alt="">
          </span>
          <span class='im-3'>
            {{detailData.name}}
          </span>
          <span class='im-5'>
              <button @click='showUpdate(detailData.date)' :disabled='detailData.editable==0' :class="detailData.editable==0?'a11':'b11'">修改</button>
          </span>
          <span class='im-4'>{{detailData.status}}</span>
      </li>
  </ul>



</div>
 <div v-show='actives'>
     <div class="updateCover"></div>
     <div class="updateOrder">
        <div class="io-1s" v-for="(item1,index1) of uorderDate" :key="index1">
           <input type="radio" id="index1" :value="item1.dishes_type+'-'+item1.date" v-model="pick" class="io-5">
              <label :for="index1"  class="io-2s">
                   <img src="" alt="">
                   <span  class="io-3s">{{item1.name}}</span>
                   <span class="io-4s">￥{{item1.pirce}}</span>
             </label>
             <br>
         
        </div> 
         
         <div class='msorders' @click='msorder()'>确认</div>
        <div class='cancelupd' @click="cancelUpd()">X</div> 
     </div>    
 </div>
  <div class='ordercover' v-show='fopen'>
      <div class="ocover"></div>
      <div class='hr-list'>
          <ul>
              <li v-for='(item,index) of listDate' :key='index' @click='findOrder(item)'>{{item}}</li>
          </ul>
     </div>
  </div>
</div>
</template>

<script>
export default {
    data(){
        return{
            weeks:['日','一','二','三','四','五','六'],
            date:{},
            num1:15,
            space1:[],
            space2:[],
            active:false,
            orderdata:[],
            inx:[],
            flag:true,
            iny:[],
            actives:false,
            uorderDate:[],
            pick:'',
            listDate:[],
            datashows:'',
            datains:'',
            fopen:false,
            am:'',
            bm:'',
            inz:32,
            detailData:{},
            active3:true,
            active4:false,
            ost1:[],
            ost2:[],
            inn:[],
            dd:'',
            mm:'',
            mms:'',
        }
    },
    created(){
      this.Getdate();
      this.mGetDate();
      this.getMorder();
      this.changeColor();
    },
    methods:{
       Getdate(){
           var otime=localStorage.getItem('otime');
           
           if(otime){
              this.datashows=otime;
           }
           else{
              var a=new Date();
              var yy=a.getFullYear();
              var mm=a.getMonth()+1;
              var mm1=mm+1;
              this.datashows=yy+'/'+mm;
           }
          //获取当前时间          
           var a=new Date();
           var yy=a.getFullYear();
           var mm=a.getMonth()+2;
           var dd=a.getDate();
           this.dd=dd;
           var dt=a.getDay();
            for(var i=0;i<5;i++){
            var s=mm-i;
            //时间过了12月的时间修改
            if(s>12){
                var s=s-12;
                var yy=yy+1;
            }
            else{
                var s=s;
                var yy=yy;
            }
            this.listDate.push(yy+'/'+s);
            console.log(this.listDate);
            this.datashow=this.listDate[1];
            }
           this.date={
               yy:yy,
               mm:mm,
               dd:dd,
               dt:dt
           }
       },
     mGetDate(){
        var a=new Date();
        var yy=a.getFullYear();
        var mm=a.getMonth()+1;
        this.datains=yy+'-'+mm;
        var ams=localStorage.getItem('ams');
        var bms=localStorage.getItem('bms');
        var ams=ams?ams:yy;
        var bms=bms?bms:mm;
        //console.log(ams);
        //console.log(bms);
        //console.log(this.bm+'ssssss');
        var d = new Date(ams,bms,0);
        var c = new Date(Date.UTC(ams,bms-1, 1));
        console.log(c.getDay())
        for(var i=0;i<c.getDay();i++){
          this.space1.push(i);
        }
        for(var i=c.getDate()+d.getDate();i<35;i++){
           this.space2.push(i);
        }
        
        this.date.dt=d.getDate();
         //return d.getDate();
        },
        changebg(index){
            var self=this;
            var uid=localStorage.getItem('uId');
            console.log(index);
           this.inz=index;
           var a=new Date();
           var yy=a.getFullYear();
           var mm=a.getMonth()+1;
           this.ms=mm;
           var dd=index+1;
           if(dd<10){
              var dd='0'+dd;
           }
            var ams=localStorage.getItem('ams');
            var bms=localStorage.getItem('bms');
            var ams=ams?ams:yy;
            var bms=bms?bms:mm;
            this.mms=bms;
            if(bms<10){
             var  mm='0'+mm;
           }
           var time=ams+'-'+bms+'-'+dd;
           console.log(time);
           this.$http.post('/api/order/riderdayorder',{userid:uid,date:time})
           .then(response=>{
               console.log(response.data);
               if(response.data.code==1000001){
                   self.active4=false;
                   self.active3=true;
               }else{
                   self.detailData=response.data.data;
                   self.active3=false;
                   self.active4=true;
               }
            })
            .catch(error=>{
                console.log(error);
            })
        },
        showAll(){
          this.active3=true;
          this.active4=false;
        },
        getMorder(){
            var self=this;
            var uid=localStorage.getItem('uId');
            var otime1=localStorage.getItem('otime1');
            var datain=otime1?otime1:this.datains;
            this.$http.get('/api/order/riderOrder?userid='+uid+'&month='+datain)
            .then(response=>{
                self.orderdata=response.data.data;
                //console.log(self.orderdata)
            for(var i=0;i<self.orderdata.list.length;i++){
                     self.inx[i]=self.orderdata.list[i].day
                    for(var j=0;j<self.date.dt+1;j++){
                        if(self.inx[i]==j){
                              self.iny[j]=j;
                              //console.log(self.iny[j])
                              self.inn[j]=j;
                    }
                    else{
                             //self.iny[j]=-1;
                    }
                    }   
                }
            
                    
               // for(var j=0;j<self.orderdata.list.length;j++){
                //   if(self.orderdata.list[j].status=='已领'){
               //        self.ost1.push(self.orderdata.list[j]);
               //    }
               // }
                  // console.log(self.ost1)   
                 //  console.log('这是测试数据')
            
                
            }).catch(error=>{console.log(error)});
        },
        changeColor(){
            
        },
        showUpdate(index){
            var num=index;
             var self=this
            this.actives=true;
            this.$http.get('/api/index/daydishes?date='+index)
            .then(response=>{
                //console.log(response.data);
                this.uorderDate=response.data.data;
                self.changebg(num);
            })
            .catch(error=>{})
        },
        cancelUpd(){
           
            this.actives=false;
        },
        msorder(){
            var self=this;
            var uid=localStorage.getItem('uId');
            this.$http.post('api/order/editorder',{userid:uid,data:this.pick})
            .then(response=>{
                if(response.data.status=='success'){
                   self.actives=false;
                   self.getMorder();
                }
            })
            .catch(error=>{console.log(error)})
        },
        findOrder(item){
           //console.log(item);
           this.datashow=item;
           this.iny=[];
           var a=item.split('/');
           this.am=a[0];
           //console.log(a);
           this.bm=a[1];
           localStorage.setItem('otime',item);
           localStorage.setItem('ams',this.am);
           localStorage.setItem('bms',this.bm);
          // console.log(this.am);
          // console.log(this.bm);
           var b=a.join('-');
           this.datain=b;
           localStorage.setItem('otime1',b)
           this.fopen=false;
           this.$router.replace('/Refresh')
        },
        findotherorder(){
            this.fopen=true;
        }
    }
}
</script>

<style>
 .calender{
     width:100%;
     overflow: hidden;
     display: flex;
     flex-direction: column;
 }
 .weeks{
     font-size:28px;
     width:100%;
     height:42px;
 }
 .weeks ul{ 
     width:100%;
     height:42px;
    
 }
 ul{padding:0;margin:0;}
  li{ 
     list-style: none;
     float:left;
     width:14.28%;
     height:42px;
     box-sizing: border-box;
 }
 .days li{
     font-size:28px;
     height:62px;
     line-height:62px;
     border:solid 2px #ececec;
     border-radius: 10px;
 }
 .hr-1{
     width:100%;
     height: 80px;
     font-size: 28px;
     padding:0 30px;
     text-align:left;
     background-color:#ececec; 
     line-height:80px;
 }
 .hr-2{
     margin:30px 0;
 }
 .hr-3{
     float:right;
     
     margin:5px 60px 0 0 ;
    
 }
 .ordercover{
     width:100%;
     height:100%;
     position:fixed;
     top:0;
     left:0;
 }
 .ocover{
     width:100%;
     height:100%;
     position:absolute;
     top:0;
     left:0;
     background:#000;
     opacity:0.4;
     z-index:100;
 }
 .hr-list{
     width:280px;
     height: 450px;
     background:#fff;
     position: absolute;
     right:20px;
     top:80px;
     z-index:19999;
     text-align: center;
     border-radius:14px;
 }
 .hr-list ul{
     width:90%;
     height:100%;
     padding:0 30px;
 }
 .hr-list ul li{
     width:90%;
     height:20%;
     font-size:30px;
     line-height:300%;
     list-style:none;
     box-sizing: border-box;
     border-bottom:solid 1px #a5a5a5;
 }
 .hr-list ul li:nth-child(5){
     border-bottom:none;
 }
 .hr-4{
   background:#ff9c00;
   color:#fff;
 }
 .hr-5{
   background:#fff;
 }
 .hr-6{
   background:#fff;
 }
 .hr-num{
   color:#ff9c00;
 }
 .iteminfo{
     width:100%;
     display:flex;
     flex-direction: column;
     font-size:28px;
 }
 .iteminfo ul{
     width:100%;
     height:144px;
 }
 .iteminfo ul li{
     width:100%;
     height:100%;
     padding:20px 0;
     clear: both;
     display:flex;
     flex-direction: row;
     border-bottom:1px solid #ececec;
 }
 .im-2 img{
    width:102px;
    height:102px;
 }
 .im-1{
     padding:10px 20px;
     display:flex;
     flex-direction: column;
 }
 .im-1 span{
     display: block;
     width:80px;
 }
 .im-3{
     padding:15px 0 0 24px;
     text-align:left;
     display:block;
     width:40%;
 }
 .im-4{
     width:15%;
     padding:20px 10px;
 }
 .im-5{
     padding-top:15px;
   display:block;
    width:20%;

 }
 .im-5 button{
     border: none;
    
     border-radius:10px;
 }
 .updateCover{
   width:100%;
   height:100%;
   background:#000;
   opacity:0.4;
   position:fixed;
   top:0%;
   left:0;

 }
 .updateOrder{
   width:500px;
   height:600px;
   background:#fff;
   position:fixed;
   top:50%;
   left:50%;
   margin:-300px 0 0 -250px;
 }
 .cancelupd{
   width:60px;
   height:60px;
   border-radius: 50%;
   border:solid 4px #ff9c00;
   position: absolute;
   right:-30px;
   top:-30px; 
   background:#fff;
   color:#ff9c00;
   font-size:50px;
   line-height:60px;   
 }
 .a11{
   background:#f5f5f5;
   color:#fff;   
 }
 .b11{
   background:#fff;
   color:#ff9c00;
 }
 .io-1s{
    display:flex;
    flex-direction: row;
   
    overflow:hidden;
    border-bottom:solid 1px #f5f5f5;
    padding:20px 30px;
 }
 .io-5s{
   margin:30px 0;
 }
 .io-2s{
     width:100%;
     display:flex;
    flex-direction: row;
    margin:0 28px;
    font-size:28px;
 }
 .io-3s{
    width:60%;
    margin:0 0 0 40px;
    padding-top:20px;
 }
 .io-4s{
    margin:20px 0 0 20%;
 }
 .msorders{
     background:#ff9c00;
     color:#fff;
     width:80%;
     margin:40px 10%;
     height:80px;
     font-size: 40px;
     line-height:80px;
     border-radius: 20px;

 }
 .hr-7{
     background:#199521;
 }
 .hr-8{
     background:#b934d5;
 }
</style>


