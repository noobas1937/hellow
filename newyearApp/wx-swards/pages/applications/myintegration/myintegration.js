
var app = getApp();
var repath = app.globalData.rePath;
Page({

  /**
   * 页面的初始数据
   */
  data: {
    flag: true,
    modalshow: true
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

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
    this.getIntegrateInfo();
  },
  getIntegrateInfo() {
      var uid=wx.getStorageSync('wid');
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
            that.setData({
              grainfo: res.data.data,
              modalshow:true
            })
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
  goConvertResult(){
     wx.navigateTo({
       url: '../result/result?rid=1',
     })
  },
  goDespoitResult(){
    wx.navigateTo({
      url: '../result/result?rid=2',
    })
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
  checkWages() {
    this.setData({
      flag: false,
    })
  },
  thinkMore() {
    this.setData({
      flag: true,
    })
  },
  msWages() {
    this.setData({
      flag: true,
    })
  },
  goMyConvertibility(){
    wx.navigateTo({
      url: '../myconvertibility/myconvertibility',
    })
  }
})