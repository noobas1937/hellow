
var app = getApp();
var rePath = app.globalData.rePath;
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
      console.log(options.id);
      var id = options.id;
      this.setData({
        yid:id
      })
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

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    this.myLuckyNumber();
  },
  myLuckyNumber() {
    var uid = wx.getStorageSync('wid');
    var yid=this.data.yid;
    var that = this;
    wx.request({
      url: rePath + '?action=lucky.get.luckynumber',
      method: 'POST',
      header: { 'X-Requested-With': 'gzh' },
      data: {
        user_id: uid,
        draw_id: yid,
      },
      dataType: 'json', 
      success: function (res) {
        console.log(res.data);
        if (res.data.code == 3) {
          if (res.data.data.length > 0) {
            that.setData({
              numList: res.data.data,
              showr: true
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