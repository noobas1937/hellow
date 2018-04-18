// pages/identity/identity.js
var app = getApp();
var rePath = app.globalData.rePath;
Page({

  /**
   * 页面的初始数据
   */
  data: {
  
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
  
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
  
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {
  
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
  
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
  
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
  
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
  
  },
  getIdCard(e){
     var idcard=e.detail.value;
     console.log(idcard);
     this.setData({
       idcard:idcard
     })
  },
  //去掉空格
  Trim(str) {
   
     return str.replace(/(^\s*)|(\s*$)/g, "");
    
  },
  msIdentity(){
    var idcard=this.data.idcard;
    
     console.log(typeof idcard);
     var idcrad = idcard.replace(/(^\s*)|(\s*$)/g, "");
    console.log(idcard);
    var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/; 
    var that = this;
    if (reg.test(idcard)) {
      wx.request({
        url: rePath + '?action=user.get.userinfo',
        method: 'POST',
        header: { 'X-Requested-With': 'gzh' },
        data: {
          idcard:idcard
        },
        dataType: 'json',
        success: function (res) {
          console.log(res.data);
          if(res.data.status=='failer'){
              wx.showModal({
                title: '友情提示',
                content: res.data.msg,
              })
          }
          else{
             var uif=res.data.data;
             wx.showToast({
               title: '激活成功',
               icon:'success',
               duration: 3000,
               success: function(res) {
                 wx.setStorageSync('idcard', uif.idcard);
                 wx.setStorageSync('eid', uif.id);
                 var  site = uif.site||uif.d5||uif.d4||uif.d3||uif.d2||uif.d1;
                 wx.setStorageSync('uinfo', { mobile: uif.contact_moblie, uname: uif.name, uid: uif.tb_user_id,site:site });
                 wx.redirectTo({
                   url: '../setphone/setphone',
                 })
               },
               fail: function(res) {},
               complete: function(res) {},
             })
             
            
          }
          

        },
      });
    }
    else {
      wx.showModal({
        title: '友情提示',
        content: '身份证输入有误',
        success:(res)=>{
          if(res.confirm){
             that.setData({
                idcard:''
             })
          }
        }
      })
    }

  },
  //获取open-id并且通过open-id获取用户个人信息；
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
          data: {
            //appid: 'wx45118101c21bea1b',
            // secret: '66c4efcfd1bee85015140a454206c172',
            // grant_type: 'authorization_code',
            // js_code: code
            code: code
          },
          header: {
            "Content-Type": "application/json"
          },
          success: function (res) {
            console.log(res.data);
            if (res.data.openid != null && res.data.openid != undefined) {
              var openid = res.data.openid;
              wx.setStorageSync('userid', openid);
              wx.getUserInfo({
                success: function (res) {
                  wx.request({
                    url: rePath + '?action=user.post.openid',
                    data: {
                      client: 'wx',
                      openid: openid
                    },
                    method: 'POST',
                    header: {
                      "Content-Type": "application/x-www-form-urlencoded"
                    },
                    success: function (res) {
                      console.log(res.data);
                      if (res.data.data == null || !res.data.data.mobile) {

                        wx.showModal({
                          content: '新用户需绑定手机号',
                          success: function (res) {
                            if (res.confirm) {
                              // 获取用户信息
                              wx.navigateTo({
                                url: '../setphone/setphone',
                              })

                            } else {

                              that.onShow();
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
})