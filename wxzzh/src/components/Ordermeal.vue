<template>
  <div class="om">
      <div class="om-1" @click="getSite()">
          <span class="om-11"><img src="../assets/img/address.png" alt=""></span>
          <span class="om-12">领餐地址</span>
          <span class="om-13">{{siteName}}</span>
          <span class="om-14">></span>
      </div>
      <div class="om-2">
          <div class="om-21">
              <span><img src="../assets/img/time.png" alt=""></span>
              <span class="om-25">领餐日期</span>
          </div>
          <ul class="om-22">
            <li v-for="(item,index) in OrdermealData" :key="index"  class="om-23">
                <span>{{item.date2}}</span>
                <span>{{item.weekday}}</span>
            </li>
          </ul>
      </div>
      <div class="om-3">
          <span>已选餐品信息</span>
      </div>
      <div class="om-4">
          <ul class="om-41">
              <li class="om-42" v-for="(item,index) in OrdermealData" :key="index">
                 <span class="om-43">
                     <span>{{item.dateday}}</span>
                     <span>{{item.weekday}}</span>
                 </span>
                 <span class="om-44"><img :src="item.image" alt=""></span>
                 <span class="om-45">{{item.cname}}</span>
                 <span class="om-46">￥{{item.price}}</span> 
              </li>
          </ul>
      </div>
      <div class="om-5">
          <span>创建时间</span>
          <span>{{date}} {{time}}</span>
      </div>
      <div class="om-6" @click="msiteinfo()">
          确认
      </div>

<div class="zcontainer3" v-show="active1"> 
   <div class="zt-1"></div>
   <div class="zt-2e">
      <span class="zt-3e">{{omsg}}</span>
      <span class="zt-4e" @click="findorder()" v-show='acc1'>查看</span>
      <span class="zt-4e" @click="getSite()"  v-show='acc2'>填写站点</span>
      <div class='zt-rm' @click="changeActive()">X</div>
   </div>
  </div>
  </div>
</template>

<script>
export default {
   data(){
       return{
        title:'西红柿番茄炒蛋盖烧饭+统一冰红茶饮品',
        date:'',
        time:'',
        OrdermealData:[],
        siteId:'',
        siteName:'',
        active1:false,
        omsg:''
       }
   },
   created(){
       this.getDate();
       this.getOrdermeal();
       this.getSiteInfo();
   },
   methods:{
      getDate(){
       var a=new Date();
       var mm=a.getMonth()+1;
       var dd=a.getDate();
       var hh=a.getHours();
       var min=a.getMinutes();
       var sec=a.getSeconds();
       if(mm<10){
           var mm='0'+mm;
       }
       if(dd<10){
           var dd='0'+dd;
       }
       if(hh<10){
           var hh='0'+hh;
       }
       if(min<10){
           var min='0'+min;
       }
       if(sec<10){
           var sec='0'+sec;
       }
       this.date=a.getFullYear()+'-'+mm+'-'+dd;
       this.time=hh+':'+min+':'+sec;
    },
    getSite(){
           this.$router.push({ name: 'Sites', params: { }})
       },
    getOrdermeal(){
        var self=this;
        var uid=localStorage.getItem('uId');
        this.$http.get('api/order/orderinfo?userid='+uid)
        .then(response=>{
           console.log(response.data);
           self.OrdermealData=response.data.data;
        })
        .catch(error=>{
            console.log(error)
        })
    },
    getSiteInfo(){
        if(this.$route.params.radio){
        var siteinfo=this.$route.params.radio;
        console.log(siteinfo)
        var a=siteinfo.split(' ');
        console.log(a);
        this.siteId=a[0];
        this.siteName=a[1];
        }
       
        
    },
    msiteinfo(){
         var self=this;
         var uid=localStorage.getItem('uId');
         if(this.siteId){
           this.$http.post('api/order/setsite',{userid:uid,siteid:this.siteId})
            .then(response=>{
              console.log(response.data.data);
              if(response.data.status=='success'){
                  self.omsg='点餐成功'
                  self.active1=true;
                  self.acc2=false;
                  self.acc1=true; 
              }
             })
            .catch(error=>{console.log(error)})
         }
         else{
             self.omsg='请选择站点'
             self.active1=true;
             self.acc1=false;
             self.acc2=true;
         }
         
    },
    changeActive(){
        this.active1=false;
    },
    findorder(){
        this.$router.push({ name: 'Morder', params: {  }})
    }
   }

}
</script>

<style>
.om{
    text-align:left;
    overflow: hidden;
}
.om-1{
  width:100%;
  height:90px;
  line-height: 90px;
  border-bottom:1px solid #e1e1e1;
  padding:0 30px;
  display:flex;
  flex-direction: row;
  font-size:30px;
}
.om-11 img{
   margin:30px 0 0 0;
}
.om-12{
    margin-left:30px;
}
.om-13{
    width:250px;
   margin-left:38px;
}
.om-14{
   margin-left:180px;
}
.om-2{
    width:100%;
    height:222px;
    padding:0 30px;
    overflow: hidden;
}
.om-2 li{
    list-style:none;
}
.om-21{
  font-size:30px;
  padding-top:30px;
}
.om-22{
  font-size:26px;
  margin:50px 0 0 20px;
  color:#fff;
}
.om-23{
  float:left;
  width:80px;
  height: 80px;
  background:#ff9c00;
  margin-left:16px;
  padding-top:10px;
  text-align:center;
}
.om-25{
    margin:20px;
}
.om-3{
    width:100%;
    height: 90px;
    background:#f5f5f5;
    font-size:30px;
    line-height:90px;
    padding:0 30px;
}
.om-4{
    width:100%;
    overflow: hidden;
    font-size:30px;
}

.om-42{
    font-size:28px;
    overflow: hidden;
    height:130px;
    width:100%;
    padding:1.8% 0 0 30px;
    display:flex;
    flex-direction: row;
    border-bottom:1px solid #e1e1e1;
}
.om-43{
    margin:15px 0 0 0;
    text-align:center;
    display:flex;
    flex-direction: column;
}
.om-44 img{
  margin:0 0 0 26px;
  width:103px;
  height:103px;
}
.om-45{
    display: block;
    width:45%;
    margin:25px 0 0 26px;
}
.om-46{
    margin-right:60px;
    margin-top:25px;
    color:#ff5a03;
}
.om-5{
    width:100%;
    height:100px;
    background:#fff;
    font-size: 30px;
    line-height:100px;
    border-top:solid 28px #f5f5f5;
     border-bottom:solid 60px #f5f5f5;
     padding:0 30px; 
}
.om-6{
    height:100px;
    width:100%;
    background: #f39d0a;
    text-align: center;
    line-height: 100px;
    font-size:30px;
    color:#fff;
}


.zcontainer3{
  width:100%;
  height:100%;
  text-align:center;
}
.zt-1{
  width:100%;
  height:100%;
  background:#000;
  opacity:0.4;
  z-index:666;
  position:fixed;
  left:0;
  top:0;
}
.zt-2e{
  width:548px;
  height:292px;
  background:#fff;
  z-index:888;
  position:fixed;
  left:50%;
  top:50%;
  margin:-146px 0 0 -274px;
  border-radius: 30px;
  display: flex;
  flex-direction: column;
}
.zt-rm{
  width:50px;
  height:50px;
  border-radius: 50%;
  position: absolute;
  right:-25px;
  top:-25px;
  border:3px solid #ff9c00;
  text-align: center;
  line-height: 50px;
  background:#fff;
  color:#ff9c00;
}
.zt-3e{
  font-size:36px;
  margin:60px 0 40px 0;
}
.zt-4e{
  display: block;
  width:60%;
  height: 80px;
  background:#ff9c00;
  font-size:30px;
  color:#fff;
  line-height:80px;
  margin:0 auto;
  border-radius:40px;
}
</style>


