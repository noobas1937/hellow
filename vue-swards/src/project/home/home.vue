<template>
<div>
<div class="ml-container">

 <div class="my-luck row">
   
 </div>
  <div class="go-new-view">
   <div  class='begin-awards fs-3 bga-1 cr-1 lef' @click='historySwards'>历史开奖</div>
   <div  class='begin-awards fs-3 bga-2 cr-1 rig' @click="goMySwards">我的夺宝</div>
  </div>
  <div class="my-cot">
  <div class="gb fs-1 column" v-show="true" v-if="lkn[0]">
   <div class="gb-line"></div>

   <div class="gb-content">
	 <img src="../../assets/img/vol.png" mode="aspectFill" class="vol lef"/>
	 <span class="cr-1 gb-1 lef">{{lkn[0].employee_name}}</span>
	 <span class="cr-1 gb-2 lef" v-if="lkn[0].status==1">抽中了{{lkn[0].award_name}}</span>
   <span class="cr-1 gb-2 lef" v-else-if="lkn[0].status==2">抽中了{{lkn[0].przecredit}}奋斗金</span>
	 <span class="gb-3 rig" v-if="lkn[0].day>0">{{lkn[0].day}}天前</span>
   <span class="gb-3 rig" v-else-if="lkn[0].day==0&&lkn[0].hour>0">{{lkn[0].hour}}小时前</span>
   <span class="gb-3 rig" v-else-if="lkn[0].day==0&&lkn[0].hour==0">{{lkn[0].minute}}分钟前</span>
   </div>
   <div class="gb-line"></div>
  </div>
    
  <div class="ml-del">
    <div class="ml-new-title">
      <div class="ml-new-line lef"></div>
      <span class="ml-title cr-1 fs-2 lef">夺宝攻略</span>
      <div class="ml-new-line rig"></div>
    </div>

     
     <div class="ml-content2">
         <img src="../../assets/img/setup.jpg" alt="" class="setup-img">
     </div>
  </div>

  <div class="ml-del ml-dels1">
     <div class="ml-new-swards">
        
       <span class="ml-new-swardstit cr-1 fs-2 bg-9">最新开奖</span>
       <div class="new-col-line1"></div>
       <div class="new-col-line2"></div>
     </div>
     
     <div class="ml-content column">

      <div class="ml-new row" style="margin:0 auto;" v-if="glswd[1]">
       <div class="mlc-con bg-1 column" v-for="(item,index) in glswd" :key="index"   @click="goNewSwards(item.award[0].lucky_draw_id)">
         <img :src="item.award[0].img_id" class="mlc1-img" mode="aspectFill"/>
         <span class="cr-2 fs-1 mlcc-tit">{{item.award[0].name}}
         </span>
         <vprocess :wid="'0'+item.apply_people/item.with_people*100"></vprocess>
         <span class="mlc1-times fs-m" v-if="timestr>item.draw.start_time">开奖时间:{{item.draw.end_date1}}</span>
         <span class="mlc1-times fs-m" v-else>开始时间:{{item.draw.start_date1}}</span>
         <div class="go-swards fs-2 cr-1">去夺宝</div>
        </div>
      </div>
      
       <div class="ml-new row" style="margin:0 auto;" v-else>
       <div class="mlc-new-con bg-1 row" v-for="(item,index) in glswd" :key="index"   @click="goNewSwards(item.award[0].lucky_draw_id)">
         <img :src="item.award[0].img_id" class="new-mlc1-img" mode="aspectFill"/>
         <div class="new-mlcc-con">
          <span class="cr-2 fs-1 new-mlcc-tit">{{item.award[0].name}}</span>
          <vprocess :wid='item.apply_people/item.with_people*100'></vprocess>
          <span class="mlc1-times fs-m" v-if="timestr>item.draw.start_time">开奖时间:{{item.draw.end_date1}}</span>
          <span class="mlc1-times fs-m" v-else>开始时间:{{item.draw.start_date1}}</span>
          <div class="new-go-swards fs-2 cr-1">去夺宝</div>
         </div>
        </div>
      </div>

      <div class="ml-all cr-1 fs-3"  @click='goSwards'>
       <span>查看全部</span>
      </div>
     
    </div>
  </div>
    

  
  
   

  <div class="ml-del">
     <div class="ml-new-title">
      <div class="ml-new-line lef"></div>
      <span class="ml-title cr-1 fs-2 lef">活动规则</span>
      <div class="ml-new-line rig"></div>
    </div>
    
     <span class="cr-1 fs-1 newrules">夺宝奖品依据市场实际价格平分相应“等份”并设置参与总份额，同一奖品每人的参与份数不设上限。购买份数达到开奖要求后，即在所有购买者中随机抽取一人拿走奖品。</span>

     <div class="ml-content1 cr-1 fs-1 column">
           <div class="new-hm-ruless bg-1 cr-2 fs-m">假设夺宝奖品总份数为100</div>
           <img src="../../assets/img/newhm.jpg" alt="" class="new-hm-img">
     </div>
  </div>



  <div class="ml-del">
     <div class="ml-new-title">
      <div class="ml-new-line lef"></div>
      <span class="ml-title cr-1 fs-2 lef">开奖规则</span>
      <div class="ml-new-line rig"></div>
    </div>
    
     
      
     <div class="ml-content1 cr-1 fs-1">
        <span>1、夺宝奖品所需总份数满额，即抽取一人拿走最终奖品。</span>
        <span>2、夺宝截止时间到，夺宝奖品所需总份数未满，但已购份数达到总份数的20%以上，将激活奋斗奖金池。此时，所有已购金额将放入奖金池中，从所有购买者中抽取1人，拿走奖金池中所有奋斗金。<span class="cr-5 fs-1" style="background:#ffff00;">（例如，活动结束时已购份数为总份数的80%，即抽取1人，获得最终奖品价格*80%的奋斗金）</span></span>
        <span>3、夺宝截止时间到，夺宝奖品已购份数未达到总份数的20%，所有购买的奋斗金将原路退还至购买者账户。</span>
     </div>
  </div>
    
</div>  

  

   
</div>

  <tabbar></tabbar>
</div>
</template>




<script>
import tabbar from'@/components/tabbar'

import screen from '../../store/screen'
import vprocess from '@/components/vprocess'


export default {
    data(){
        return{
          lkn:[],
          glswd:[],
          widths:'80',
          timestr:''
        }
    },
    
    components:{
        tabbar,
        vprocess,
       
    },
    computed: {
       width(){
          return screen.state.with;
       },
       height(){
          return screen.state.height;
       }
    },
    beforeCreate(){
        screen.commit('changeScreen');   
    },
    created(){
       // this.getEid(); 
        this.getLuckyInfo();
        this.goSwardsLIst();
    },
    methods:{
        getEid(){
          
        },
        historySwards(){
           this.$router.push({
             name:'swardshistory'
           })
        },
        goMySwards(){
            this.$router.push({
             name:'myswards'
           })
        },
        goNewSwards(id){
           console.log(id)
           var rid=id;
           this.$router.push({
               name:'rewarddel',
               params:{
                   rid:rid
               }
           })
        },
        goSwards(){
           this.$router.push({
              name:'rewardlist'
           })
        },
        getLuckyInfo(){
            var self=this;
            this.$http.get('?action=lucky.get.luckyhistory')
            .then(res=>{
               if(res.data.status=='success'){
                  self.lkn=res.data.data
               }
              })
            .catch(err=>{console.log(err)})
       },
       goSwardsLIst(){
          var date = new Date();
            var time3 = Date.parse(date);
            this.timestr=time3 / 1000;
           var self=this;
            this.$http.post('?action=lucky.get.newsttwo')
            .then(res=>{
               if(res.data.status=='success'){
                  self.glswd=res.data.data
               }
              })
            .catch(err=>{console.log(err)})
       },
       
    }
}
</script>



<style>
.ml-container{
  background:#ff6b11;
  padding-bottom:60px;
}
.newrules{
  padding:10px;
  display: inline-block;
  line-height: 45px;
  margin-bottom: 40px;
}
.my-luck{
   width:100%;
   height:790px;
   background: url('https://open.connect-city.com.cn/wxappimg/banner.jpg')no-repeat center;
   background-size: cover;
   position: relative;
  
}
.new-mlc1-img{
  width:280px;
  height:280px;
 }
 .new-mlcc-con{
   width:270px;
   margin-top:27px;
   margin-left:18px;
 }
.new-hm-ruless{
    
    width:60%;
    height:60px;
    line-height: 60px;
    border-radius: 30px;
    text-align: center;
    position: absolute;
    left:50%;
    top:0%;
    transform: translateX(-50%) translateY(-50%);
    -webkit-transform: translateX(-50%) translateY(-50%);
    
    
 }
.new-mlcc-tit{
   height:80px;
   overflow: hidden;
   text-overflow: hidden;
   display: -webkit-box;
   -webkit-line-clamp: 2;
   -webkit-box-orient: vertical;
}
.mlc1-times{
  text-align: left;
  overflow: hidden;
  text-overflow: hidden;
  white-space: nowrap;
  margin-top:4px;
}
.new-hm-img{
  width:100%;
  height:490px;
}
.new-go-swards{
  width:225px;
  height:65px;
  border-radius: 14px;
  text-align: center;
  line-height: 65px;
  background:#ff4800;
  border-radius: 12px;
  border:2px solid #000;
  margin-top:26px;
}
.mlc-new-con{
  display: flex;
  flex-direction: row;
  width:590px;
  height:280px;
  border:4px solid #000;
}
.go-new-view{
   height:150px;
  padding:0 40px;
}
.begin-awards{
  width:290px;
  height:95px;
 
  /*background:linear-gradient(-180deg,#fecc37,#a14921);*/
  line-height: 95px;
  text-align: center;
  margin:0 auto;
  font-weight: bold;
  border-radius: 47px;
  
}
.ml-new-swards{
  width:100%;
  height:96px;
  position: relative;
}
.ml-new-swardstit{
  width:206px;
  height:60px;
  border-radius: 30px;
  text-align: center;
  line-height:60px;
  border: 3px solid #000;
  position: absolute;
  left:50%;
  top:0;
  margin-left:-103px;
  font-weight: bold;
}
.new-col-line1{
  width:5px;
  height:30px;
  position: absolute;
  left:40%;
  top:66px;
  background: #000;
}
.new-col-line2{
  width:5px;
  height:30px;
  position: absolute;
  right:40%;
  top:66px;
  background: #000;
}
.ml-new-title{
  width:100%;
  height:50px;
  margin-bottom:20px;
}
.ml-new-line{
  width:36%;
  height:2px;
  background: #000;
  margin-top:20px;
}
.ml-title{
  display: block;
  width:26%;
  margin:0 1%;
  text-align: center;
}
.setup-img{
  width:100%;
  height: 112px;
}
.bga-1{
 
  box-shadow: 0 10px #a805ad;
  background:linear-gradient(#f88efe,#e346e9,#cf04d6);
}
.bga-2{
 
  box-shadow: 0 10px #0469b0;
  background:linear-gradient(#77c6fe,#44a9ef,#0b88df);
}
.my-cot{
  padding:0 40px;
}
.gb{
  height:60px;
  padding:0 10px;
 
 
 
}
.gb-content{
   height: 50px;
   line-height:50px;
   margin:2px 0;
}
.gb-line{
   width:100%;
   height:2px;
   background:linear-gradient(90deg,#fff,#999999,#fff);
}
.gb-1{
  margin-left: 16px;
}
.gb-2{
  display: block;
  width:360px;
   margin-left: 10px;
  text-align: center;
  overflow: hidden;
  text-overflow: hidden;
  white-space: nowrap;
}
.gb-3{
  color:#f5f5f5;
}
.gb image{
  width:30px;
  height:30px;
  vertical-align: middle;
  margin-top:10px;
}
.ml-content{
  box-shadow: 0 0 8px #000;
  padding:30px 15px;
  border-radius: 5px;
  
}
.mlc-img{
  width:100%;
  height:100%;
}
.ml-del{
  margin-top:50px;
  
}
.ml-dels1{
  position: relative;
  
}

.mlc-con{
  width:270px;
  height:450px;
 
  margin:0 15px;
  text-align: center;
  padding: 16px 10px 0 10px;
  position: relative;
  border:2px solid #000;
}
.mlc1-img{
  width:228px;
  height:228px;
  margin:0 auto;
}
.go-swards{
  width:100%;
  height:52px;
  background:#ff4800;
  text-align: center;
  line-height: 52px;
  position: absolute;
  left:0;
  bottom:0;
  
}
.mlcc-tit{
   height:80px;
   vertical-align: middle;
   text-align: left;
   padding:10px 0;
   overflow: hidden;
   text-overflow: hidden;
   display: -webkit-box;
   -webkit-line-clamp: 2;
   -webkit-box-orient: vertical;
}
.mlc-pro{
  border-radius:5px;
  margin:0 0 70px 2%;
  width:96%;
}
.mlc-num{
  display: block;
  width:100%;
  text-align: center;
  margin-bottom:58px;
}
.ml-content{
  background:#fb9a33;
  
}
.ml-new{
 
}
.ml-content1{
  border-radius: 5px;
  box-shadow: 0 0 8px #fff;
  padding:30px 15px;
  line-height: 55px;
  margin-bottom:100px;
  position: relative;
}
.ml-content2{
 
  
 
  
 
  

}
.ml-content2-1{
  background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAB2CAYAAAADbleiAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAABmJLR0QAAAAAAAD5Q7t/AAAACXBIWXMAAAsSAAALEgHS3X78AAAZV0lEQVR42u2deZQcxX3Hv7+q7jl2d6TRibQckhAWh0AEI3MIEA7GcWwMBGLAPJPYwRA7yYuA59h++IgdE3LYGN6LY2zjI3GwecYOGDsc5slGxhZCB6DTEhKS0DlC0h6zO7s7szPdVfmju2aqa3pmdle7OyvRv/fqdfUx3dX1md+vfvWr6m7CCSBSyhiAaf4q+SlwSK2f1timUhcROc2+v2MRq9kFGK5IKS0AM+BBZP5mOvzDH046+pOfLBjcv3+Ok82eIgYGZstSaZp03SnSddMkZQIAIEQCjBW8X9EAcZ4ly+qmWKyDJ5MZPnny/vicOXtn3XHHjmk33GBJKdWfQPipg4jksArdRKFjP8XYi5QyBSAFH2jvypUt++67b1F+x47FpY6Od4pC4RzpOCeP8k0Jsu39LJHYbE+b9mpiwYJXznj44Y2J+fMdeKAlgD4iGmh2/dSTCQtYStkCYIpfRtp117K5XU8//d7S0aPvdvv7L4KU8Xo3QDXyoddqsF45EfXxlpaX7BkzVky/6abn5/77Vw+jotm9RDTY7HqrVw9NFyklAZgNT1PZ/vvvn3Xo+9/7s9KhQ9eKwcFzwwpNNZbHXJYaS00ESyTW2Sed9MuT77r75+13390ND7RLRJ3Nrsuwumqa+GDbATBRKPCNV7/n3f2bNt3m9vW9mwBuFpRQ7UkRje7NlMHK4LZQ4ERF3tb2bOt55/3o/JUvvYSKVnc2u71uKmApJQcwCwAb3L8/tvnaD16T3779b8Tg4FlVABGEGgb0WLW5kdZKVIDXgk2x2ObE3LmPLHz6maeSZ5xRBODC88abAropgHWN7duwIbHtI7felN+1669lqTQnABEGVAxBk2vcWK0blSH5ULAhx8o6+8m298RPO+3Bc59+9snkggVFAKIZpnvcAUsppwNIikKBr798yXX9mzd/VjpOey2wJkCGcE2tB3Q4TlYjuPVg1wC9o/Xcc798wSuv/QaeNpeIqHe86nvcAPv911kA+NabP7So87ln/0nk8xfUg6rvYyHHAfUhH1N5tWUjmLLOPiUskXhh2nXXf/6sx36yE4ADz+sWY13v4wJYSjkTQKLrV8+1bb/j9k8V33rro9CcJzOFwWxkukfzhmSdvA5OoD7cKtBEBXv69IfO/b9n/iO1+F0FAA4R9Y1l3Y8pYL+tPRkA33Lj9Rd2Pf+rh2SxeJp+YWVya4FlxnFAY7DDBV6rH1yvfW4EtB5osu1N6aVXfuK8Xy3fBg9y/1gxGDPA0gsNzigePmy/dsniuwYPHvw7eF5zTXj69nraW285khuUDfbV8q5NTa6VhHGcVzAq2NOmfeHSQ0cfAVCC1za7o82BHfspQipFymkAZma+9c0Za886438GDxxYBim5DpABYARw8pYqz6l6OyOvW8RCEtVIjfYP5Vg0uK5acqPsZuLqeGiWSMpEqaPjgZWp5Pd23b2sHIYdbRl1DZZSngKAb/vobecd+dnjj0jHmV2rna3SYgo3yQCqnKjRbndr3k+DfC2TLWS1FuvabLTNW+zp029Ykjm6sz2dtDLZ/KiNYI1q3Si4ry1d8r7eNasfgpQtteDqgGuBbRbUmvdXJx8GWsALjJgm2jTpvnS4wPW3tSbXALAy2fyoxLVHrZ58uNa6Cxd9uH/L5n+GZ5lCwQYcKwpqNDDxwIber7FUeX17AKysBiyqzzMwCNz6sZbk8wB4Jps/5pGqUakvBXfNwnfcmd+1815UmrBy4jC0FtVmGvCAm4WbKFBD791Yqrwe0tSB6tAFQiEX88DHb08mfw4P8jEFRfgx36APd/XCd3xCwTXNram55pKgOTaYmBo75PpQ5Q4pOPn7a4VWfQvALeD697jurmcs6/VUwm7NFZz8SMtzTPWn2tw1i868feCNHf8IVLRR/XMYKtqrtpvOVViBjjewQOM2WtRZhnSlnA6i25fFE8/C0+QjIynTiOtRSjkbQOyVSy+8Mbfhta/D19wyPArCraW5ZkGOR7BVdWMsVb4e6DJkGYBc3Evsw/fG4y8BKGWy+e7hlmVEJlpKOQlA64Zr3nt5dvWqbwLgZncnrN01AxhKTiS4ofWl3V/DIcngMCifBPn+eUK+8DLnPamEXcwVnGEFQ4YN2B80mL7jU8tOP/LET38MKVuYT0y5zWXNDQkkqKUKIpiBhRMpkbHU8yzsWB1yZXv8JMir8kRP7WSMpxJ2LlcYejd5JNGTWV0rft2W+a9HviOlmKQKwrWCq4iO6gbpcOvd/ImYwu4zLDKn1xXTfuNHyk69xS3952QpbQBzhgNrWBospWwHwNddduHX3f7+y/RQn1py8wbw9oU7FE0ua27IMbomc8Kcy4Ube4Zbq3zPOjeqgP2pq8nVixfeVDi4f5lqZ1VMWcVkw8xxIOz4NoMbmkwZ4vFxYPGZUqxfyay3Ugm7fyjt8ZBMtD/sN2nHPyyb179921fCysAaLMs3UuMe3y5CRj2Y9WfmCSg7rQTQ2VI8cKlw0xiiqR5qG3ySyOdZ5tHvf5UgW81RmFqjLLpDFcGtSBmy1s7q9aVbQ9KOJwIswoyPitK9AKg9nTyl0bUaApbeBHO25tJFHxH5gUv0tiEQfarRxsiQbVEKrx8dOBRcbbs6NkXyxk+L4hUAUu3pZN1mdigaPHn/Nx+aUtiz+1719zPHTk1nyhxPjeDWhmyOO4dps75NHbsQ7hdmQcYBnFUPXl36UsopAKyNH/rAl0Qhf7H6VzFog9hU7VyFDZo3uzIncgqrJ4k69eaBS58r3cJysl7z+8ahneNGTxeyjR++7qxSb/Y2raGvtAnBa1YC6RRcP24exWuSBKJa/gpRMAKm6pGkZ6olgNmQdy6B+9QqL5C4OezcNU203y1C94u/+Sw0TVcXC4srKzOtHxtJYyFtqT+xoeo0LO+n1ltk6eMAqD2dbA07d902eN3VF5/vFgb+tF5bYIYd9YI22/QdTymgGCH1rYc49XqfRvLmq+DMAnDGkAH7njNyWzbeDc8qVBdIg2h6hMqERGmYSau/UP8ljAMhfh05twFg7elkbEiAAci1Vy2eK4uD15S1E+GOFAspSKS9I0tUZ2l62Pr+6ZB/vpBECsDChoDb00kCIPq2bvpbEFj5hMwAy6ovdKKOCjUDsp5n2jLMVBNDy0eodAMA8vmVJdSL/t2ZMxPCKd2qTLFU/wSqWIqy2dbVHpGMhoSNGSvvGagwUTsJQDuJGznwmOs9tXlQ7Q4z0azQdfQWECaH9WfNCFatf1+URp5MLSbUYKDVf4xw2id4cTG8NyRUYOor7elkAp72/6X6rVqyGuukbY9k9CRQr36mamoxBev+HBIfhGemy91a00Sz+/jgbACXh1ELzDqAZio0Ux7J6Ik+/VbxkMYBekAkDXnlTJLxI5LmA9gBVJtoNoOJm0GV7bUGFRq571E69lQrph9mtn3Hq/UveOkyAOkyUJVpTyctAMwmfKDeg1o6ZBjbIxkDCQOO4LqubHOYWAJUvGndRE++mjtJTrjYvIakBuvNroQTXMKaP31bubmUwCSSSzhALjAVQKcOmP0Jd5YSEFdmt/zHqLMeyTiL1u7qFl3622PA9Bus0jv+17EBoDPwWFAbYQkM+44Qe6+751Eav1Sr22pORT6Xi/P9X3ltcHs6yQDwGMlLQv81mqrWeslIJGMvjd40oGQGyfNVXpnomacyEeeERWqH2ccKM9ORiR5nMZ5cK/tb/nYFOkVyITxHK65MdPw62zmbEeyyaUa49xxFNpooYZ4zqrtLNmH2OVxMAnCa0mA+i8kz9VgnoE0bQWU4K8yji2T8pJF5Vnwustx5W13WqwBTK8kzyipPmqKaHnOkvRNCdM9Zt9wqfyoXcwFsUoBZwp9IXevBZTLyEefmiW5FTQ5q+yRCO1BxshgnebKkkF9RxWMmVAc5Ihl/qfeiNsDT7BaSswFQBTArf9SiWoupRj6S5okx0KC0msFTwpjw3pZv+X1gxglTA+Y3pO0NsI1AN0e0abXaaqUN9icA2CQnA56JTkxjMgZCqz4EqJ/APFngjJGMq5ivaqrq1ZAH2SZvRIkBSM1kMhlwpCJ4E1bMedPmUgWhGCEBeBqcSjOZNDWS6nSPIv5NFoOFNJb+bgu+k5VoIWmHhh4j52rCitlVMvvCDIgDHmUh/XCmfrD5r5DGvkiaK7UiWUAQvgWg/6igfGAvgp60uS+S5ki9AAcQhC2Afvgmum9QUnAqiHlW1DlrJOMqptcchkcfXLIA5HY4rE8SBKl3gTbS3Aj0uMtw9IwAlCR6AZCVyeaL7ekkuUCP5X0rMNAGq3WgRr8rkqZKLQ124X26pzya5AJZizzANX9Zaz2ScRez72vucyR6AO2Z4hLo6FC5yUiFmyoKatVnD7TJAAWgA9CmzRYkMilWOUGktRNHzLcmmFobNoUqJ+kwoAHOSTow3Whdq96Eqp0wUuLxFx1gOWpF4dA7XDoAaG3wYZf2nG7XOWPYeiTjK1Td9gZeloaKtm8vsb1ABbCzuch3X5p0Q08amo9kXCQA1JjZygztVd+7kEBp+QDfD+APysl6fWWBH3aBbD2GkXPVXDFhKker/K4yf31A0O6cIAfqyYZMNt8HAH2CNqkTRdJ8qTkv3YSr5RkB3YK2AEAmmxf6s0l01KVNaS6XmgENfWqIeoNONPFu7CUQd6ZgO0vGUu8yvel4gAHj+eAtRfaavh4BnBiiK5N6L6hackODiSCf7rM2AcgCwcdHt/+iz2bXtjldFmFq1RXCrhrJmEtgloZaanB12AQgJ+j19QWWBbANCGpwpwsgK2itOrG+VBL5WeMrAe9Za3u5ocHqhbAZh9YBQCabLwAa4Ew2LwFgZ5G9GCnnxJCA1wxDawFYvvdsKcgAVvTzl/RzmO/oOPRYzl4rgL4IcvMl4DEbptkizYP2QRck9j3aY+0CsEGdwwT8ZrdLpS6Xflu+CFVfNJKxl1p9Xv1D1Ja/bvnH7CmxFwAgk813qfMEAGeyeRcA1hX4c+oi5gUjGR8x+7tlsKhA1TWZiMSjPdbz5nnC3nS3/ce99paCxO5ofnRzxNReZra7qAAnxgHLRoew1i7v40cBrNbPVQVYfeVyV4k9FXbBSMZW9AiV/llepa3KseIEELdAlg2ybLw4wJ8CgEw236+fr9brhHu+m40970r0hI4/UgR7rESvV6bBDLS9Cq4dAywb/eB7/uUwvQpgu3m+WoD/cMSlwb0OezKCO36im2W9O8S1ZMIF51gzQD91JSS0t8wqCQWcyeYdAOIHPfaTAsgB2nSQZtfCCSp6xMp0rHTzzBjz4NoxgFvIgx/8wj7nNwAOqliGLvW+2bBmZ5H1vVlkjwde6RDJmIjeFCpTbMLljEB2HLBjnhZzjpV98od9rnQRYp6BOoAz2XwRQOEb2dgTjvQmcJkFqffR50iGLqZpJgRNctmp8s0ycQvgFvok23nvrvxvAewN016g8ZfPXsk4VFg/yL+tz+IzwVZ9TLHZNXYcSZjXrIcgLX+dLBuw/HbXsgHG5M+OFB8uSggAu2qdvy5gvy0+8EBn7IV+QVsD7YSZUA07kvpSri8KarBulr12l5fbXbJsgHFkiljx4L78ZgB/qKW9wNC+XbjLBeTPctYDIJTMeUCBcJoxZygCXVuqnCrSRoagmWZGoFi8ApdbcEC5L+/q+zYAmcnm36p3nYaA/X/Hhl/2WXt2FdmP9cKFtcGMgp5gBLlazBizDpYTYLOK9gY0l1sAY/htd/FbL/eUugGsanStIX0/OJPNZwH0fbkj9tiAoNfN7yepdU7hoCPIFTGtWyC+TICtt7t2DLDjlT4vYzhSki/f83rvcgB7Mtl8vtH1hvqBaAB4NSfI+X6Pfb8EBvQwmhlSU4UOi6W+nUGb8WVVT3p7qyAzboEUXG4BjGNQovPTr/c8CM807xzKNflQDgKAXMGRqYTdubvEUvNs+dYcWy4N+/p3mAYDeNu/2EX3lnXN1aGqxDkHxRNArKK9ksj5USb/pScPF/YCWJkrOO5QrjtkwACQKzjFVMJmv8/z7NJW0TaVy3PM7lPgBdUwnC/jT/B2EdNbrg+XgWIJIJbwnCu/3V3bU/r2vdt7XwSwJZPN9wz12sMCDAC5gtOdStizl/dbG69pc89r5ZgdpslAeF5/79aJLrojas6CDIXLCBRPenCVaeYc+wti+S0bun4A4FAmm39zOGUYNmAASCXsAw4wd/0gW3V1q3tJjDBFNz/6JGy93wxUHq+Aoc0n2mQ+s2dhDvnZNeHGvXCkD7fbkRtuWt91f0FIJ5PNrxluOUYEOFdwkErYB7tcmrXfYauXtpxxgitOljdq2ZhJlrbpirkRJAqrTWDFwiBy1ko3H4Xuz++uftzBwpuAcCKXMEZth6MCDAA5AqOm0rYR/aV2JROl62+tMW9wia06IFy0rRYn7trwjW1+XiVgD+itbOmp2wzD2xMOVSqzdXhCuz9+63Zz2zKlXoB/M6PKg5bRgwYAHIFp5RK2J1vFFmqT9DaS5LiCoshGegfI6pxEuLj1PQptZSSMjRNuDaDGCWBUpUt7l5gf33bM1+Zk1PsRvAykw2PzjSsh0TYADIFZzBVMLu3jrIWjtctmpJi3tpjNBW1mTtpsteNkJMN8LN9kQGHWaOwxwps821COCxuKe5eiCDc/S7tHPZtuxnV2eLXQBWDSWYUU+OGTAA5ApOIZWwO7cXWWp3if1+aau7OM4oTYyBGAUDHSGgdbhmtwqYeKBD21kEp9RwDabFNLiMwLRuUGUIkCPrYPMdW7o+v6G31ANPc48JLjBKgIGyJh/ZU2JT1xb4ij9O4cwW25pNjIM4B4j5oGU14JD2OWDWQ0z3eAPXoaq+LIWA1dvZgOYqkxxPAvFEZVaGZYMYw8GCWHHzhs6v7Mu7eXht7ojNslnuUZX2dDIO4PI2Bv7oPHxyfpLd4HWApZeEC+k4kMKFkIALwJV+gvfiTFcCAn7y89JfApXPrurv7xrtbpb+R1J5plkU3Wk0HwbTB+k51EyMmDeeqw8ceH98sb63+N9/tanrcdcb212h5qeP5n2MqvhfvrwMQPzrp/Gr3pPm9zBGSQCA6wLC9UGXIIWoBowKWNcHKdQSQdhA8Du7jb5nUO/mTetgvptZHyoNTGvV216t/WWkTZCzY4G4MhiDA+p5PJO//992924AcBTAxnpjuxMGsJL2dPJ0APNuns5PvavdvjdlsQUAfMACcB0PdKkIIWVAe8tabIDWYZeTrNZi8yMVNSugRj9cdwLViIyutXofnyPYDqsJ6WXnSU2z8R0pIkJHUb76xR09X/t992AnvEH7Q2PBYMybsvZ0cjKAxZM5Wd9ZkLjtnFZ+KwFcCgFI4Wm060C6DuCUAqC9V/JVIAsZDljUMdeN3isS9g6qcl7v2mlQy/FkHypB01jGtOk1VnkGhoopS1BhTU/pB5/c0vUL3ySvymTzA2NV/+Piq/gf/ngXgLaPzYqdfufs2D2TLDobAKTreqAdX5t90FKBRsUkm21ymEYD4ZpsgjbBmkvTJJvdO46Kk8WUxlpKSy1tgN4zx8QYjhbF2q/tzn3j2aOFtwAcArB1tE2yKePqjLank1MBXBBnoAfnt7zv4sn27XGSUwFZAe1rtO6M6YDNvA45ANS4dtgn4MLWdYfKhKt7/8pcg1u++bUq+YoDBTCGgkBmeefgdz+3veclvyhrMtl8bjzqfNy7l74DdiaAk+clWPJf57fcclYLv5ETWiAFIASk3ka7rrfuOpBSlqEqzTY/d6tr6lBVQ68E0ywrwFzLE+NeW6o01LIqeQ2sI9GzMVd6/J5tPU91l0QJwBuZbH7veNZ30+IH7emkBeASAPGzW3jr5+clrz+n1brRhkxDCkBKSCE8h0z3vNU2ITxPWveu/XOb5njIXrTmVJlhVQVPwVXmlzgvm2Ed7JZc6YkvvtH7iz15dwBAJzwPWWCcpekBovZ0MgavfU6cEmfxL81ref8FKX5TnNFJnor6Wu33oaFMue+keabdO05qZGVVpv7dB0y0p6blttMD6EPUAjdq3bPbDAOuPPBab+mJ+3flfu2PAPUDWDfSgYLRkKYDVuJr9CIAU5KM2OfmJi9eMtl6/4wYu4gAqwxbabaCLHzdFaIMWokM2Gst76sqhX3Dj7EAYA8e90CXofp5IgiiwUzBfemFzsHnHtzTt9F/CKwD3syLpoHV7mhiid9GzwEwHwAuTFmT7zw5ccXZrdaVU2xaRFJ64VUF1o+QSRUpU9uGVQtaB4nIA898Q60DBwDGIIiKHUXx6uZc6cWH9/W/vKPfGfAujK0A3hprz3hYt9bsAtQTP+z5RwDaAODsVt76sdmJdy5ss941M0aLkoxOKR88HKj15gzpsP11CYh+gT2HB8WmDbnSuu8dGNh4oOAOAmVt3ZrJ5kvNrq/Q22l2AYYqflu9AMBJattFk6zJ186InzMvyefNiPH5kyya08JpNgPiVd+nH4p4fe+BASEPZUtiz5GieHN7n/PGTw/nX9854CotBYB9AN6cCCa4kRw3gE1pTycT8IDPMPctSdtTzmuzZ85K8PQUm6XaLDYpzigGABZR3JFyEAAKAoWc4+a6S6L3YMHtXt9bOrq+t9SLatdsH7yJ5hNSS+vJcQu4lrSnk0kAp/ppuPImvAepR2WobiLI/wN9CZiQ3N+4HAAAAABJRU5ErkJggg==)no-repeat;
  background-size: cover;
  width:120px;
  height:120px;
  margin:0 15px;
  display: flex;
  flex-direction: column;
  text-align: center;
}
.mlct-1{
  margin-top:10px;
  font-weight: bold;
}
.mlct-2{
  margin-top:0px;
}
.ml-all{
  text-align: center;
  margin-top:40px;
}
.sign-in{
   width:140px;
   height:140px;
   border-radius: 50%;
   background:linear-gradient(-180deg,#ffe682,#ce4500);
   position: absolute;
   right:-48px;
   top:-35px;
   text-align: center;
   display: flex;
   flex-direction: column;
   box-shadow:0 0 20px rgba(0,0,0,1);
  }
  .si-img{
    width:40px;
    height:40px;
    margin:20px 0 2px 50px;
  }
  .si-txt{
    font-weight: bold;
  }
  .myc-sn{
    width:100%;
    height:100%;
    position: fixed;
    left:0;
    top:0;
   
  }

  .myc-sn image{
    width:100%;
    height:100%;
  }
  .ml-content2-2{
    margin-left:30px;
  }
  .vol{
    width:48px;
    height:40px;
    margin-top:8px;
  }
</style>

