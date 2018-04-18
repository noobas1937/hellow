
var app = getApp();
var repath = app.globalData.rePath;
Page({

  /**
   * 页面的初始数据
   */
  data: {
     s1:3
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
    this.getSwardsListInfo();
    this.getSwardsListInfo1();
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
  getSwardsListInfo() {
    var that = this;
    var uid=wx.getStorageSync('wid');
      wx.request({
        url: repath + "?action=lucky.get.luckdrawprizerecord",
        method: 'GET',
        header: { 'X-Requested-With': 'gzh' },
        data: {
          draw_id: 2,
          user_id:uid,
          isticket:1,
          
        },
        dataType: 'json',
        success: function (res) {
          console.log(res.data);
          if (res.data.status == 'success') {
            that.setData({
              mytickets: res.data.data
            })
          }
          else{
             that.setData({
               mytickets1:'没有人中奖' 
             })
          }

        },
      })
  },
  getSwardsListInfo1() {
    var that = this;
    var uid = wx.getStorageSync('wid');
    wx.request({
      url: repath + "?action=lucky.get.luckdrawprizerecord",
      method: 'GET',
      header: { 'X-Requested-With': 'gzh' },
      data: {
        draw_id: 2,
        user_id: uid,
        isticket: 1,
        isrecord:1,
      },
      dataType: 'json',
      success: function (res) {
        console.log(res.data);
        if (res.data.status == 'success') {
          that.setData({
            mytickets1: res.data.data
          })
        }
        else {
          that.setData({
            mytickets1: '没有人中奖'
          })
        }

      },
    })
  },
   getDateDiff(dateTimeStamp){
    var minute = 1000 * 60;
    var hour = minute * 60;
    var day = hour * 24;
    var halfamonth = day * 15;
    var month = day * 30;
    var now = new Date().getTime();
    var diffValue = now - dateTimeStamp;
    if(diffValue < 0){ return; }
	    var monthC = diffValue / month;
    var weekC = diffValue / (7 * day);
    var dayC = diffValue / day;
    var hourC = diffValue / hour;
    var minC = diffValue / minute;
    if(monthC>=1) {
      result = "" + parseInt(monthC) + "月前";
    }
	else if(weekC>=1) {
      result = "" + parseInt(weekC) + "周前";
    }
	else if(dayC>=1) {
      result = "" + parseInt(dayC) + "天前";
    }
	else if(hourC>=1) {
      result = "" + parseInt(hourC) + "小时前";
    }
	else if(minC>=1) {
      result = "" + parseInt(minC) + "分钟前";
    }else
	result= "刚刚";
    return result;
  },
  getDateTimeStamp(dateStr){
    return Date.parse(dateStr.replace(/-/gi, "/"));
  }
})