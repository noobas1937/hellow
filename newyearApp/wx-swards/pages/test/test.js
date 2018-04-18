
var app = getApp();
var rePath=app.globalData.rePath;
Page({
  data:{
     num:56,
     flags: true,
    },
onLoad(){
  // this.judgePermission();
  // this.userLogin();
},
goIDentity(){
   wx.navigateTo({
     url: '../identity/identity',
   })
},
onShow(){
  
 // this.judgeIsUser();
  this.userLogin();
  this.goSwardsLIst();
  //this.getUserInfos();
  this.getLuckyInfo();
  this.getAwardInfo();
  //this.judgeUserIdentity();
},
onReady(e){
     
     
      },
//判断用户是否激活身份

//判断用户是否认证

//获取用户报名信息
enlistInfo(){
   var uid=wx.getStorageSync('wid');
   var that=this;
   wx.request({
         url:rePath+'?action=lucky.get.luckyapply',
         method: 'POST',
         header: { 'X-Requested-With': 'gzh' },
         data: {
           user_id:uid,
           draw_id:2,
        },
         dataType: 'json',
        success: function(res) {
          //console.log(res.data);
          if(res.data.code==3){
            wx.showModal({
              title: '报名成功', 
              success: (res) => {
                that.onShow();
              },
            });
          }
          else{
             wx.showModal({
              title: res.data.msg, 
              success: (res) => {
                that.onShow();
              },
            });
          }         
    },
    });
  },
//获取抽奖信息
getAwardInfo(){
  
    var uid = wx.getStorageSync('wid');
    var that = this;
    wx.request({
      url: rePath + '?action=lucky.get.luckyapplyinfo',
      method: 'POST',
      header: { 'X-Requested-With': 'gzh' },
      data: {
        user_id: uid,
        draw_id: 2,
      },
      dataType: 'json',
      success: function (res) {
       // console.log(res.data);
        that.setData({
          ei: res.data.data,
        })

      },
    });
  
 
  
 },
goMyPrize(){
  wx.navigateTo({
    url: '../myswards/myswards',
  })
},
signIn(){
  var that=this;
   this.setData({
     flags:false,
   })
   var timer=setInterval(function(){
     that.setData({
       flags: true,
     });
     clearInterval(timer);
   },3000); 
},
goNewSwards(e){
  var id=e.currentTarget.dataset.info;
  //console.log(id);
  wx.navigateTo({
    url: '../goodluckdetail/goodluckdetail?sid='+id,
  })
},
userLogin() {
  var that = this;
  wx.login({
    success: res => {
      // 发送 res.code 到后台换取 openId, sessionKey, unionId
      var code = res.code;
      wx.request({
        //url: "https://api.weixin.qq.com/sns/jscode2session",
        url: rePath + '?action=lucky.get.openid',
        //method: "GET",
        method: "POST",
        header: { 'X-Requested-With': 'gzh' },
        data: {
          //appid: 'wx45118101c21bea1b',
          // secret: '66c4efcfd1bee85015140a454206c172',
          // grant_type: 'authorization_code',
          // js_code: code
         code:code
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
                  url: rePath + '?action=user.post.newopenid',
                  data: {
                    client: 'wx',
                    openid: openid
                  },
                  method: 'POST',
                  header: {
                    "Content-Type": "application/x-www-form-urlencoded"
                  },
                  success: function (res) {
                   // console.log(res.data);
                   // console.log('我是get_openID');
                         if(res.data.code==3){
                             wx.setStorageSync('uid', res.data.data.id);
                             wx.setStorageSync('wid', res.data.data.employee_id);
                             //var id=res.data.data.id;
                             // that.getEmployeInfo(id);
                         }
                         else{
                            //that.onShow();
                           // console.log('我是get_own_openID');
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
                         // console.log("授权结果")
                        }
                      })
                    } else if (res.cancel) {
                      //console.log("用户点击取消")
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


getDetailInfo() {
  var that = this;
   var uid=wx.getStorageSync('uid');
  //console.log(uid)
  wx.request({
    url: rePath + "?action=user.post.user_id",
    method: 'POST',
    header: { 'X-Requested-With': 'gzh' },
    data: {
      user_id: uid,
    },
    dataType: 'json',
    success: function (res) {
     // console.log(res.data);
      wx.setStorageSync('mymobile1', res.data.data.mobile);
     that.onShow();
    },
  })
},
//获取员工信息
getEmployeInfo(id) {
 // var uid = wx.getStorageSync('uid');
  //var mobile = wx.getStorageSync('mymobile1');
  var that = this;
  wx.request({
    url: rePath + '?action=user.get.employee',
    method: 'POST',
    header: { 'X-Requested-With': 'gzh' },
    data: {
      user_id: id,
     
    },
    dataType: 'json',
    success: function (res) {
      console.log(res.data);
      if (res.data.status == "success") {
        wx.setStorageSync(
          'wid',
          res.data.data.id
        );
        //that.getSwardsList();
        that.getUserInfos();
      }
      else {
           wx.redirectTo({
             url: '../identity/identity',
           })
      }



    },
  });
},




getUserInfos() {
  var that = this;
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
        nickname: nickName,
        avatarUrl: avatarUrl
      })
      wx.setStorageSync('nickname', nickName);
      wx.setStorageSync('avatarUrl', avatarUrl);
    }
  })
},
  goSwards(){
    wx.navigateTo({
      url: '../goodluckprize/goodluckprize',
    })
  },
  historySwards(){
    wx.navigateTo({
      url: '../swardshistory/swardshistory',
    })
  },
  goSwardsLIst(){
    var that=this;
    wx.request({
      url: rePath + '?action=lucky.get.newsttwo',
      method: 'POST',
      header: { 'X-Requested-With': 'gzh' },
      data: {
      },
      dataType: 'json',
      success: function (res) {
      //  console.log(res.data);
        that.setData({
          glswd:res.data.data
        })
      },
    });
  },
  //判断用户是否已经授权      
  
  
  getSwardsList() {
    var uid = wx.getStorageSync('wid');
    var that = this;
    wx.request({
      url: rePath + '?action=user.get.luckdrawrecord',
      method: 'POST',
      header: { 'X-Requested-With': 'gzh' },
      data: {
        user_id: uid,
        draw_id: 1,
      },
      dataType: 'json',
      success: function (res) {
       // console.log(res.data);
        that.setData({
          awardsList: res.data.data
        })
      },
    });
  },
  getLuckyInfo() {
  
    var that = this;
    wx.request({
      url: rePath + '?action=lucky.get.luckyhistory',
      method: 'GET',
      header: { 'X-Requested-With': 'gzh' },
      data: {
        
      },
      dataType: 'json',
      success: function (res) {
      //  console.log(res.data);
        that.setData({
          lkn: res.data.data
        })
      },
    });
  },
  onShareAppMessage: function () {
    return {
      title: '踢踢员工激励系统',
      desc: '奋斗，为了更好的明天',
      path: '/page/test/test'
    }
  },
})