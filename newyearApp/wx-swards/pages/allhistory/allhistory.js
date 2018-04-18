
var app = getApp();
var repath = app.globalData.rePath;
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
    this.getSwardsTicketsInfo();
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
  getSwardsTicketsInfo() {
    var that = this;
    if (wx.getStorageSync('wid') && wx.getStorageSync('wid').id) {
      var uid = wx.getStorageSync('wid').id;
      console.log(uid)
      wx.request({
        url: repath + "?action=user.get.alldrawrecord",
        method: 'POST',
        header: { 'X-Requested-With': 'gzh' },
        data: {
          user_id: uid,
        },
        dataType: 'json',
        success: function (res) {
          console.log(res.data);
          if (res.data.status == 'success') {
            that.setData({
              mytickets: res.data.data
            })
          }

        },
      })
    }

  },
})