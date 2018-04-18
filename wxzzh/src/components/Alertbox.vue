<template>
  <div class="zcontainer" v-show="active">
   <div class="zt-1"></div>
   <div class="zt-2c">
      <span class="zt-3c">身份认证，享受特权</span>
      <span class="zt-4c" @click="goidentity()">去认证</span>
      <div class='zt-rm' @click="changeActive()" v-show='false'>X</div>
   </div>
  </div>
</template>

<script>
import apiConfig from '../../config/api.config'

export default {
    data(){
      return{
        active:true,
        statusinfo:'',
        token:'',
        uid:''
      }
    },
    beforeCreate(){
     
    },
    created(){
     this.wxlogin();  
     this.getIdentity();
     this.getUinfo();
    },
    methods:{
      getUinfo(){
         var uid=localStorage.getItem('uid');
         if(uid){
           this.active=false;
         }
         else{
            this.active=true;
         }
      },
      getIdentity(){
      var ide=localStorage.getItem('identity');
      if(ide=='未认证'){
        this.active=false;
        this.statusinfo='身份认证，享受特权'
      }
      else{
        this.active=true;
      }
      },
      changeActive(){
        this.active=false;
      },
      goidentity(){
        this.$router.push({ name: 'Identity', params: {  }})
      },
      wxlogin(){
            var usid=localStorage.getItem('uid')
            var self=this;
            var url = location.hash; //判断?后面是否有参数
            console.log(apiConfig.murl);
            if(url.indexOf("?") == -1){
              if(!usid){

                 window.location.href='https://'+apiConfig.murl;

                // window.location.href='https://www.ggjrfw.com';

              }
              else{
                self.active=false;
              }
             
            }
            else{
            //console.log(url) //获取url中"?"符后的字串
             var str = url.substr(1); //从第一个字符开始 因为第0个是?号 获取所有除问号的所有符串 这里会获得类似“id=1”这样的字符串
             var strNum= str.split("=");   //用等号进行分隔 （因为知道只有一个参数 所以直接用等号进分隔 如果有多个参数 要用&号分隔 再用等号进行分隔）
              console.log(strNum[1]); 
              var uid=strNum[1];         //直接弹出第一个参数 （如果有多个参数 还要进行循环的）
             localStorage.setItem('uid',uid);
             this.$http.post('api/auth/isauth',{userid:uid})
             .then(response=>{
               if(response.data.data.Id==0){
                      self.active=true;
               }
               else{
                  console.log(response.data)
                   var uinfo=response.data.data;
                   var uId=uinfo.Id;
                   var uname=uinfo.name;
                   var siteid=uinfo.siteid;
                   var umaster=uinfo.stationmaster;
                   var siteid=uinfo.site_id;
                   localStorage.setItem('identity','已认证');
                   localStorage.setItem('uId',uId);
                   localStorage.setItem('uname',uname);
                   localStorage.setItem('umaster',umaster);
                   localStorage.setItem('usiteid',siteid);
                   localStorage.setItem('bsiteid',uinfo.blesite_id);
                   localStorage.setItem('sitename',uinfo.site_name);
                   self.active=false;   
               }
               
              })
             .catch(error=>console.log(error))
            
            }
            
           
        
      },
    
    //setCookie(cname, cvalue, exdays) {
      ///var d = new Date();
     // d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
     // var expires = "expires=" + d.toUTCString();
     // console.info(cname + "=" + cvalue + "; " + expires);
     // document.cookie = cname + "=" + cvalue + "; " + expires;
     // console.info(document.cookie);
     // },
    // getCookie(cname) {
     ///  var name = cname + "=";
     //  var ca = document.cookie.split(';');
     //  console.log(ca)
      // for (var i = 0; i < ca.length; i++) {
      //  var c = ca[i];
      //  while (c.charAt(0) == ' ') c = c.substring(1);
      //    if (c.indexOf(name) != -1) return c.substring(name.length, c.length);
       //         }
       //         return "";
       //     },
    }
}
</script>

<style>
 .zcontainer{
  width:100%;
  height:100%;
 
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
.zt-2c{
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
.zt-3c{
  font-size:36px;
  margin:60px 0 40px 0;
}
.zt-4c{
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


