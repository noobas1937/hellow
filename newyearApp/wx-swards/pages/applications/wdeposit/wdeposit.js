// pages/applications/wdeposit/wdeposit.js
var app = getApp();
var repath = app.globalData.rePath;
Page({

  /**
   * 页面的初始数据
   */
  data: {
    modalshow: true
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
    this.getDepositInfo();
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
          if (res.datacode==3) {
            that.setData({
              desinfo: res.data.data,
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
  getMyMoney(e){
     var id=e.detail.value;
     this.setData({
       money:id,
     })
  },
  getMyRmb(e){
    var id = e.detail.value;
    this.setData({
      money: id,
    })
  },
  msMyDespoit(){
     
     if (!this.data.money){
         wx.showModal({
           title: '友情提示',
           content: '请输入提现金额',
         })
     }
     else{
       var money = this.data.money;
       var uid = wx.getStorageSync('wid');
      
         var that = this;
         wx.request({
           url: repath + '?action=user.post.withdraw',
           method: 'POST',
           header: { 'X-Requested-With': 'gzh' },
           data: {
             user_id: uid,
             money:money
           },
           dataType: 'json',
           success: function (res) {
             console.log(res.data);
             if (res.data.status==3) {
                  wx.showModal({
                    title: '友情提示',
                    content: res.data.msg,
                    success:(res)=>{
                      that.onShow();
                    }
                  })
             }
             else{
               wx.showModal({
                 title: '友情提示',
                 content: res.data.msg,
               })
             }

           },
         });
       }
    
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