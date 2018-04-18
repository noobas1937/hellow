// pages/newestactivity/newindex/newindex.js
var app = getApp();
var repath = app.globalData.rePath;
var oimg = app.globalData.oimg;
var timer;
var timers;
Page({

  /**
   * 页面的初始数据
   */
  data: {
     flag1:true,
     flagy: true,
     oimg:''
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
    this.myTimers();
    this.getScore();
    this.setData({
       oimg:oimg
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
  goNewRule(){
    wx.navigateTo({
      url: '../newrule/newrule',
    })
  },
  getCredits(){
    this.setData({
      flagy: false
    })
    var that=this;
    wx.showToast({
      title: '',
      icon: 'loading',
      duration: 2500,
      success:(res)=>{
      }
    })
    clearTimeout(timers);
    timers=setTimeout(function(){
      that.getLuckyInfo();
    },2500)
     
  },
  catchMySwards(){
    wx.navigateTo({
       url: '../../goodluckprize/goodluckprize',
     })
  },
  close(){
    //this.getScore();
   // this.setData({
    //  flag1: true,
    //  flagy: true
    //})
    wx.switchTab({
      url: '../../test/test',
    })   
  },
  myTimers(){
    var that = this;
    var id = wx.getStorageSync('wid').id;
    console.log(id)
    wx.request({
      url: repath + '?action=lucky.get.newyeardrawinfo',
      method: 'POST',
      header: { 'X-Requested-With': 'gzh' },
      data: {
        user_id: id,
      },
      dataType: 'json',
      success: function (res) {
        // console.log(res.data);
        that.setData({
          awardsList: res.data.data
        })

        var status = res.data.data.type;
        var time = res.data.data.time;
        var t1 = time.hour, t2 = time.minute, t3 = time.second;
        clearInterval(timer);
        timer = setInterval(function () {
          if (t2 >= 0 && t3 > 0) {
            t3--;
          }
          else if (t2 > 0 && t3 == 0) {
            t3 = 59;
            t2--;
          }
          else if (t1 > 0 && t2 == 0 && t3 == 0) {
            t3 = 59;
            t2 = 59;
            t1--;
          }
          else if (t1 == 0 && t2 == 0 && t3 == 0) {
            t3 = 0;
            t2 = 0;
            t1 = 0;
            that.onShow();
          }
          that.setData({
            t1: t1,
            t2: t2,
            t3: t3,
            txt: txt
          })
        }, 1000)
        if (status == 1) {
          var txt = '开始倒计时';
          that.setData({
            flagt: false,
            flagy:false
          })
        }
        else if (status == 0) {
          var txt = '暂无活动';
          that.setData({
            flagt: true,
            flagy: false
          })
        }
        else if (status == 2) {
          var txt = '结束倒计时'
          that.setData({
            flagt: false,
            flagy: true
          })
        }
      },
    }); 
  },
  getLuckyInfo() {
  
      var uid = wx.getStorageSync('wid');
      var that = this;
      wx.request({
        url: repath + '?action=lucky.get.luckydraw',
        method: 'POST',
        header: { 'X-Requested-With': 'gzh' },
        data: {
          user_id: uid,
          draw_id: 1,
          isticket: 1,
        },
        dataType: 'json',
        success: function (res) {
          console.log(res.data);
          if (res.data.status == "success" && res.data.data.id == 0) {
            //wx.setStorageSync('isHave', true)
            that.setData({
              swinfo: res.data.data,
              flag1: false,
              
              swf: res.data.data.msg
            })

          }
          else if (res.data.status == "success" && res.data.data.id != 0) {
            wx.setStorageSync('isHave', true)
            that.setData({
              swinfo: res.data.data,
              flag1: false,
            })
          }
          else {
            that.setData({
              flag1:true,
              flagy: true
            })
            wx.showModal({
              title: '温馨提示',
              content: res.data.msg,
            })
          }
          that.myTimers();
          
        },
      });
   

  },
  getScore(){
   
      var uid = wx.getStorageSync('wid');
      var that = this;
      wx.request({
        url: repath + '?action=lucky.get.fdjcount',
        method: 'POST',
        header: { 'X-Requested-With': 'gzh' },
        data: {
          user_id: uid,
        },
        dataType: 'json',
        success: function (res) {
          console.log(res.data);
          if(res.data.status=='success'&&res.data.data!=null){
               that.setData({
                 scount:res.data.data.count
               })
          }
          else{
            that.setData({
              scount: 0
            })
          }
        },
      });
   
  }
})