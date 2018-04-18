
var app = getApp();
var repath = app.globalData.rePath;
Page({

  /**
   * 页面的初始数据
   */
  data: {
      flag:true,
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
    this.getRewardInfo();
    console.log(new Date());
    var timestamp = Date.parse(new Date());
    console.log(timestamp/1000);
  },
  getRewardInfo() {
    var uid = wx.getStorageSync('wid');
   
      var that = this;
      wx.request({
        url: repath + '?action=user.get.salarydetail',
        method: 'POST',
        header: { 'X-Requested-With': 'gzh' },
        data: {
          user_id: uid,
          type:1,
        },
        dataType: 'json',
        success: function (res) {
          console.log(res.data);
          if (res.data.code ==3) {
            that.setData({
              wginfo: res.data.data,
              modalshow: true
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
  mSureExchange() {
    var uid = wx.getStorageSync('wid');
    var money = this.data.snum;
  
      var that = this;
      wx.request({
        url: repath + '?action=user.post.conversion',
        method: 'POST',
        header: { 'X-Requested-With': 'gzh' },
        data: {
          user_id: uid,
          money: money
        },
        dataType: 'json',
        success: function (res) {
          console.log(res.data);
          if (res.data.status = "success") {
            wx.showModal({
              title: '友情提示',
              content: res.data.msg,
              success: (res) => {
                wx.switchTab({
                  url: '../../application/application',
                })
              }
            })
          }
          else {
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
  
  },
  checkWages(e){
    var id=e.currentTarget.dataset.info;
    var mnum = this.data.wginfo.record[id].credits;
    console.log(id);
    if(id){
      this.setData({
        flag: false,
        rid: id,
        mnum:mnum
      })
    }
   
  },
  thinkMore(){
    this.setData({
      flag: true,
    })
  },
  msWages(){
    var uid = wx.getStorageSync('wid');
    var rid=this.data.rid;
  
   
      var that = this;
      wx.request({
        url: repath + '?action=user.post.userpointconfirm',
        method: 'POST',
        data: {
          user_id: uid,
          recordid:rid,
        },
        dataType: 'json',
        success: function (res) {
          console.log(res.data);
          if (res.data.code==3) {
             wx.showModal({
               title: '友情提示',
               content: '确认成功',
               success:(res)=>{
                 that.setData({
                   flag: true,
                 })
                 that.onShow();
               } 
             })
          }else{
            wx.showModal({
              title: '友情提示',
              content: '确认失败',
              success: (res) => {
                that.setData({
                  flag: true,
                })
                that.onShow();
              } 
            })
          }

        },
      });
   
   
  },
  goWagesDel(e){
    var id=e.currentTarget.dataset.info;
    console.log(id);
    wx.navigateTo({
     url: '../mywagedel/mywagedel?id='+id,
    })
  }
})