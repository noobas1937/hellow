

function getCookie(){
   var cookie=document.cookie;
       console.log(typeof cookie);
        var cookies=cookie.split(';');
        console.log(cookies)
       if(cookies[0]){
          console.log('获取cookie成功');
          var eid=cookies[0].split('=');
          var uid=cookies[1].split('=');
          console.log(eid);
         localStorage.setItem('uid',eid[1]);
         localStorage.setItem('eid',uid[1]); 
       }
       else{
          window.location.href="https://open.connect-city.com.cn/wechatweb/user/index"
       }
}


export default getCookie;