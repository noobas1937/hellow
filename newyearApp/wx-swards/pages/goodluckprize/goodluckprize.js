// pages/goodluckprize/goodluckprize.js
var app = getApp();
var repath = app.globalData.rePath;
Page({

  /**
   * 页面的初始数据
   */
  data: {
     page:0,
     page_size:5,
     
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var date = new Date();
   
    var time3 = Date.parse(date);
   
    this.setData({
      timestr: time3 / 1000,
    })
  },
  onShow(){
    this.goodLuckList();  
    var date=new Date();
    console.log(date);
    var time3 = Date.parse(date);
    console.log(time3+'time');
    
  },
  goodLuckList() {
    var that = this;
   
      var uid = wx.getStorageSync('wid');
      console.log(uid);
      wx.request({
        url: repath + '?action=lucky.get.luckyapplyrecord',
        method: 'GET',
        header: { 'X-Requested-With': 'gzh' },
        data: {
          user_id: uid,
        },
        dataType: 'json',
        success: function (res) {
          console.log(res.data);

        },
      });
   
   
    
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
    this.goSwardsLIst();
  },
  goSwardsLIst() {
    var that = this;
    wx.request({
      url: repath + '?action=lucky.get.newsttwo',
      method: 'GET',
      header: { 'X-Requested-With': 'gzh' },
      data: {
         limit:30,
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
  goSwardsRN(e){
    var id = e.currentTarget.dataset.info;
    console.log(id);
    wx.navigateTo({
      url: '../goodluckdetail/goodluckdetail?sid=' + id,
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
    wx.showNavigationBarLoading() //在标题栏中显示加载

    //模拟加载
    setTimeout(function () {
      // complete
      wx.hideNavigationBarLoading() //完成停止加载
      wx.stopPullDownRefresh() //停止下拉刷新
    }, 1500);
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