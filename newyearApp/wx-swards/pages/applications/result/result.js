// pages/applications/result/result.js
var app = getApp();
var repath = app.globalData.rePath;
Page({

  /**
   * 页面的初始数据
   */
  data: {
     modalshow: true,
     showr:true
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    if(options.rid&&options.rid==1){
      wx.setNavigationBarTitle({
        title: '使用记录',
      })
      this.getIntegrateInfo();
      
    }
    else if (options.rid && options.rid == 2){
      wx.setNavigationBarTitle({
        title: '提现记录',
      })
       this.getDepositInfo();
    }
    
  },
  goHello: function () {
    wx.redirectTo({
      url: '/pages/guide/guide',
    })
  },
  goIdentity: function () {
    wx.redirectTo({
      url: '/pages/identity/identity',
    })
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },
  getIntegrateInfo() {
    var uid = wx.getStorageSync('wid');
 
      var that = this;
      wx.request({
        url: repath + '?action=user.get.pointdetail',
        method: 'POST',
        header: { 'X-Requested-With': 'gzh' },
        data: {
          user_id: uid,
        },
        dataType: 'json',
        success: function (res) {
          console.log(res.data);
          if (res.data.code ==3) {
              if(res.data.data.record.length>0){
                that.setData({
                  grainfo: res.data.data,
                  showr:true,
                })
              }else{
                that.setData({
                   showr: false
                  })
              }
            
          } else if (res.data.code == 1122) {
            that.setData({
              modalshow: false
            })

          }
          else {
            wx.showToast({
              title: res.data.msg,
            })
          }

        },
      });
   
  },
  getDepositInfo() {
      var uid = wx.getStorageSync('wid');
      var that = this;
      wx.request({
        url: repath + '?action=user.get.balancedetail',
        method: 'POST',
        header: { 'X-Requested-With': 'gzh' },
        data: {
          user_id: uid,
        },
        dataType: 'json',
        success: function (res) {
          console.log(res.data);
          if (res.data.code == 3) {
            if(res.data.data.record.length>0){
              that.setData({
                desinfo: res.data.data,
                modalshow: true,
                showr:true,
              })
            }
            else{
              that.setData({
                showr: false
              })
            } 
          }else if(res.data.code==1122){
            that.setData({
              modalshow: false
            })
          }
          else{
            wx.showToast({
              title: res.data.msg,
            })
          }
        },
      });
   
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
  
  }
})