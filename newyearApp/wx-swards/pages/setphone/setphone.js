var app=getApp();
var repath=app.globalData.rePath;

Page({
  data: {
    num:'获取验证码',
    flag1:false,
    flas:true,
    flag3:true,
  },
onLoad() {
    this.getUserInfos();
},
getUserInfos(){
     if(wx.getStorageSync('uinfo')){
       var sin = wx.getStorageSync('uinfo');
       this.setData({
         celnumber: sin.mobile,
         uname:sin.uname,
         site:sin.site
       })
     }
},
getIcode(){
  //console.log(1111);
    var celnumber=this.data.celnumber;
    console.log(celnumber)
    var i=60;
    var that=this;
     var ms=/^1[3|4|5|7|8][0-9]\d{8}$/;
     if(!ms.test(celnumber)){
         wx.showToast({
           title: '输入有误',
         })
       
     
    }
    else{
      wx.request({
       url:repath+"?action=user.post.telcode",
       method: 'POST',
       header: { 'X-Requested-With': 'gzh' },
      data: {
      mobile:celnumber 
     },
    dataType: 'json',
    success: function(res) {
      if(res.data.status=='failer'){
         wx.showToast({
           title: '发送失败',
           duration:3000,
         })
      }
       console.log(res.data);
   },
  });
    }
    var yinterval=setInterval(function(){
        if(i>0){
            i--;
            that.setData({
              num:i+"S",
              flag1:true
            })
           
        }
        else if(i==0){
           that.setData({
              num:'重新获取',
              flag1:false
            })
          
           clearInterval(yinterval) 
        }
    },1000)  
  
},
getCode(e){
 var code=e.detail.value;
 this.setData({
   code:code,
 })
},
setMobile(){
  var code=this.data.code;
  var mobile=this.data.celnumber;
  var uid=wx.getStorageSync('uid');
  var eid = wx.getStorageSync('eid');
  var that=this;
  var nickname=wx.getStorageSync('uinfo').uname;
  var idcard = wx.getStorageSync('idcard');
  console.log(nickname);
  wx.request({
    url: repath +"?action=wechatweb.post.userbind",
    method: 'POST',
    header: { 'X-Requested-With': 'gzh' },
    data: {
      eid:eid,
      uid:uid,
      client:'wx',
      code:code,
      mobile:mobile,
   },
    dataType: 'json',
    success: function(res) {
       console.log(res.data);
      
       
       if(res.data.code==3){
         wx.setStorageSync('wid',eid);
         that.setData({
           flas:true,
         })
         wx.showToast({
           title: '绑定成功',
           icon: 'success',
           duration: 2000,
           success: function (res) {
             wx.switchTab({
               url: '../test/test',
             })
           }
         })

       
          
          }
        else{
          that.setData({
           flas:false,
         })
          wx.showToast({
            title: res.data.msg,
            icon:'loading',
            duration:2000,
            success:function(res){
               
            }
          })
        
        }
   },
  });
},
getCelNumer(e){
    var num=e.detail.value;
    console.log(num);
     this.setData({
        celnumber:num
      })
  },

});
