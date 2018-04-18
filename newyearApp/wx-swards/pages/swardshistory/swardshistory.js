
var app = getApp();
var repath = app.globalData.rePath;
Page({

  /**
   * 页面的初始数据
   */
  data: {
    showr: true,
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
    this.historyList();
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
  historyList() {
    var that = this;
    wx.request({
      url: repath + '?action=lucky.get.luckyhistory',
      method: 'POST',
      header: { 'X-Requested-With': 'gzh' },
      data: {
        
      },
      dataType: 'json',
      success: function (res) {
        if (res.data.code == 3) {
          if (res.data.data.length > 0) {
            that.setData({
              glswd: res.data.data,
              showr:true,
            })
          }
          else {
            that.setData({
              showr: false
            })
          }

        } else {
          wx.showModal({
            title: '友情提示',
            content: res.data.msg,
          })
        }

       
      },
    });
  },
})