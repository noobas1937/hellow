//index.js
//获取应用实例
var app = getApp();
var rePath=app.globalData.rePath;
Page({
  data: {
    awardsList: {},
    userInfo: {}
  },
  //事件处理函数
  gotoLottery: function() {
    wx.switchTab({
      url: '../canvas/canvas',
      
    })
  },
onLoad(){
  

  },
onShow() {
  this.getUserInfo();
 
   
    var that = this
    
    var pnum = wx.getStorageSync('mymobile1');
    if(pnum){
        this.getEmployeInfo()
      }
    else{
      this.userLogin(); 
      }
     if(wx.getStorageSync('wid')){
      this.getSwardsList();
    }
    
    },
gotest(){
      wx.switchTab({
        url: '../canvas/canvas', 
      });
      },

userLogin() {
  var that = this;
  wx.login({
    success: res => {
      // 发送 res.code 到后台换取 openId, sessionKey, unionId
      var code = res.code;
      wx.request({
        url: "https://api.weixin.qq.com/sns/jscode2session",
        method: "GET",
        data: {
          appid: 'wx5313a9e9e1ba4b5d',
          secret: '683f6fbd6e3c768fe10547e59b6cbbaf',
          grant_type: 'authorization_code',
          js_code: code
        },
        header: {
          "Content-Type": "application/json"
        },
        success: function (res) {
          // console.log(res.data);
          if (res.data.openid != null && res.data.openid != undefined) {
            var openid = res.data.openid;
            wx.setStorageSync('userid', openid);
            wx.getUserInfo({
              success: function (res) {
                wx.request({
                  url: app.globalData.urlID + 'user.post.openid',
                  data: {
                    client: 'wx',
                    openid: openid
                  },
                  method: 'POST',
                  header: { 'X-Requested-With': 'gzh' },
                  header: {
                    "Content-Type": "application/x-www-form-urlencoded"
                  },
                  success: function (res) {
                    console.log(res.data);
                    if (res.data.data == null) {
                      // console.log(app.globalData.userInfo.nickName)
                      wx.showModal({
                        content: '新用户需绑定手机号',
                        success: function (res) {
                          if (res.confirm) {
                            // 获取用户信息
                            wx.navigateTo({
                              url: '../setphone/setphone',
                            })
                          } else {

                          }
                        }
                      })

                    } else {
                      wx.setStorageSync('uid', res.data.data.id);
                      wx.setStorageSync('user_info', res.data.data.info);
                      wx.setStorageSync('user_mobile', res.data.data.mobile);
                      var id = res.data.data.id;
                      that.getDetailInfo(id); 
                     
                    }
                  }
                })
              },
              fail: function (res) {
                wx.showModal({
                  title: '',
                  content: '部分功能需用户授权才能正常使用',
                  success: function (res) {
                    if (res.confirm) {
                      wx.openSetting({
                        success: (res) => {
                          console.log("授权结果")
                        }
                      })
                    } else if (res.cancel) {
                      console.log("用户点击取消")
                    }
                  }
                })
              }
            })
          } else {
            console.log("获取用户openId失败");
          }
        }
      })
    }
  })
},

getUserInfo(){
   var  that=this;
   if(wx.getStorageSync('userid')){
    var userid=wx.getStorageSync('userid').data.userid;
   }
   
   //console.log(userid);
   wx.request({
         url:rePath+"?action=user.post.openid",
         method: 'POST',
         header: { 'X-Requested-With': 'gzh' },
         data: {
           openid:userid,
           client:'wx'
        },
         dataType: 'json',
         success: function(res) {
            console.log(res.data);
            if(res.data.data){
               wx.setStorageSync(
                 'uid',
                 {id:res.data.data.id}
                );
                var id=res.data.data.id;
                that.getDetailInfo(id);   
            }
            else{
              wx.navigateTo({
                url:'../setphone/setphone'
             });
            }
         },
       });
      },
getDetailInfo(uid){
  var that=this;
  
  console.log(uid)
  wx.request({
         url:rePath+"?action=user.post.user_id",
         method: 'POST',
         header: { 'X-Requested-With': 'gzh' },
         data: {
            user_id:uid,
        },
         dataType: 'json',
         success: function(res) {
            console.log(res.data);
             wx.setStorageSync('mymobile1',res.data.data.mobile);
            that.onShow();
      },
    })
  },
getEmployeInfo(){
  var uid=wx.getStorageSync('uid');
  var mobile=wx.getStorageSync('mymobile1');
  var that=this;
  wx.request({
         url:rePath+'?action=user.get.employee',
         method: 'POST',
         header: { 'X-Requested-With': 'gzh' },
         data: {
           user_id:uid,
           mobile:mobile
        },
         dataType: 'json',
        success: function(res) {
          console.log(res.data);
          if(res.data.status=="success"){
            wx.setStorageSync(
              'wid',
              { id: res.data.data.id, point: res.data.data.points }
            );
            that.getSwardsList();
          }
          else{
            wx.showModal({
              title: '提示',
              content: res.data.msg,
            })
          }
           
          
         
    },
    });
  },
getSwardsList(){
   var uid=wx.getStorageSync('wid').id;
   var that=this;
  wx.request({
         url:rePath+'?action=user.get.luckdrawrecord',
         method: 'POST',
         header: { 'X-Requested-With': 'gzh' },
         data: {
           user_id:uid,
           draw_id:1,
        },
        dataType: 'json',
        success: function(res) {
          console.log(res.data); 
         that.setData({
          awardsList:res.data.data
         })   
    },
    });
  },
getAwardItem(e){
    var info=e.target.dataset.info;
    var a=info.split(' ');
    console.log(a);
    var uid=a[2],
        aid=a[1],
        rid=a[0];
  var that=this;
  wx.request({
         url:rePath+'?action=lucky.get.getprize',
         method: 'POST',
         header: { 'X-Requested-With': 'gzh' },
         data: {
           user_id:uid,//用户id
           record_id:rid,//纪录id
           award_id:aid//奖品id
        },
        dataType: 'json',
        success: function(res) {
          console.log(res.data); 
          that.onShow();
    },
    });
  },
 getUserInfo(){
   var that=this;
   wx.getUserInfo({
     success: function (res) {
       var userInfo = res.userInfo
       var nickName = userInfo.nickName
       var avatarUrl = userInfo.avatarUrl
       var gender = userInfo.gender //性别 0：未知、1：男、2：女
       var province = userInfo.province
       var city = userInfo.city
       var country = userInfo.country
       that.setData({
          nickname:nickName,
          avatarUrl: avatarUrl
       })
       wx.setStorageSync('nickname', nickName);
       wx.setStorageSync('avatarUrl', avatarUrl);
     }
   })
 }
})
