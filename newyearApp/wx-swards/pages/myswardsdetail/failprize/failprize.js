// pages/myswardsdetail/failprize/failprize.js
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
    var sid = options.sid;
    console.log(sid)
    this.historyList(sid);
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
  historyList(sid) {
    var that = this;

    var uid = wx.getStorageSync('wid');
    wx.request({
      url: repath + '?action=lucky.get.luckyapplyinfo',
      method: 'POST',
      header: { 'X-Requested-With': 'gzh' },
      data: {
        user_id: uid,
        draw_id: sid
      },
      dataType: 'json',
      success: function (res) {
        console.log(res.data);
        that.setData({
          glswd: res.data.data
        })
      },
    });


  },
  goMyIntegration(){
      wx.redirectTo({
        url: '../../applications/myintegration/myintegration',
      })
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