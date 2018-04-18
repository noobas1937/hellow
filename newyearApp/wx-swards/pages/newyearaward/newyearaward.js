
var app = getApp();
var repath = app.globalData.rePath;
var timer;
Page({

  /**
   * 页面的初始数据
   */
  data: {
      animationData: {},
      img_url:'../../image/greyegg.png',
      flag1s:true,
      flag2s:true,
      flags:true,
      animationData1:{},
      flagw:false,
      flagey:true,
     

  },
  /**
   * 生命周期函数--监听页面加载
   */
  onShow: function () {
    this.getSwardsTicketsInfo();
    this.beginSward();
  },
  closeInfo(){
     this.setData({
       flag1s: true,
       flag2s: true,
       flags: true,
     })
  },
  findMyOwnSw(){
    var that=this;
    wx.navigateTo({
      url: '../allhistory/allhistory',
      success:function(){
        that.setData({
          flag1s: true,
          flag2s: true,
          flags: true,
        })
      }
    })
  },
  
  onReady: function () {
  
  },
  getMyRotate(){ 
      this.setData({
        img_url1: '',
      })
      this.animation.rotateZ(45).step();
      this.setData({
        animationData: this.animation.export(),
      })
      this.animation.rotateZ(-35).step();
      this.setData({
        animationData: this.animation.export(),
      })
      this.goldEgg();
    
   
  },
  getMyTool(e){
    var ids = e.currentTarget.dataset.info;
    this.setData({
      ids:ids,
      img_url1: '',
      animationData: {},
    })
  },
  goldEgg(){ 
    var that=this;
    var timer1=setInterval(function(){
      that.setData({
        img_url1: '../../image/boomegg.png',
        animationData: {},
      })
      clearInterval(timer1);
      that.getLuckyInfo();  
    },1000)
    
  },
  getLuckyInfo() {
   
      var uid = wx.getStorageSync('wid');
      var that = this;
      wx.request({
        url: repath + '?action=lucky.get.luckydraw',
        method: 'POST',
        header: { 'X-Requested-With': 'gzh' },
        data: {
          user_id:uid,
          draw_id: 1,
          isticket:1,
        },
        dataType: 'json',
        success: function (res) {
          console.log(res.data);
          if(res.data.status=="success"&&res.data.data.id==0){
            //wx.setStorageSync('isHave', true)
            that.setData({
              swinfo: res.data.data,
              flags: false,
              flag1s: false,
              flag2s: true,
              swf: res.data.data.msg
            })
            
          }
          else if (res.data.status == "success" && res.data.data.id != 0){
            wx.setStorageSync('isHave', true)
            that.setData({
              swinfo: res.data.data,
              flags:false,
              flag1s: true,
              flag2s: false,
              
            })
          }
          else{
            wx.showModal({
              title: '温馨提示',
              content: res.data.msg,
            })
          }
           that.setData({
             animationData: {},
             img_url1: '',
             ids:4
           })
           that.getSwardsTicketsInfo();
           that.beginSward();
        },
      });
  
   
  },
  
  onLoad: function (option) {
    //console.log(222222)
    this.getSwardsTicketsInfo();
   
    var animation = wx.createAnimation({
      duration: 500,
      timingFunction: 'ease',
    })
    this.animation = animation;
    //console.log(this.data.dayy)
    //var ddy=parseInt(this.data.dayy);
  },
  
  
  onHide: function () {
  
  },

  
  onUnload: function () {
  
  },

  onPullDownRefresh: function () {
    wx.showNavigationBarLoading(); //在标题栏中显示加载
     var that=this;
    //模拟加载
    setTimeout(function () {
      // complete
     // console.log(111112233444);
      that.onLoad();
      that.onShow();
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
  
  },
  goRule(){
    wx.navigateTo({
      url: '../newyears/newyearrule/newyearrule',
    })
  },
  goMenu(){
    wx.navigateTo({
      url: '../newyears/newyearlist/newyearlist',
    })
  },
  getSwardsTicketsInfo() {
    var that = this;
   
      var uid = wx.getStorageSync('wid');
      console.log(uid)
      wx.request({
        url: repath + "?action=lucky.get.luckyticket",
        method: 'POST',
        header: { 'X-Requested-With': 'gzh' },
        data: {
          user_id: uid,
          draw_id:2
        },
        dataType: 'json',
        success: function (res) {
          //console.log(res.data.data.day);
           if(res.data.status=='success'){
              that.setData({
                 mytickets:res.data.data.total,
                // dayy:res.data.data.day,
              })
           }
           else{
             that.setData({
               mytickets: 0,
             })
           }
         
        },
      })
    
    
  },
  inMemberSwardsList(){
    var that = this;
    
      var uid = wx.getStorageSync('wid');
          wx.request({
            url: repath + "?action=lucky.get.luckeprizerecord",
            method: 'POST',
            header: { 'X-Requested-With': 'gzh' },
            data: {
              user_id: uid,
              draw_id: 2,
              isticket: 1,
            },
            dataType: 'json',
            success: function (res) {
              console.log(res.data);
              if (res.data.status == 'success') {
                that.setData({
                  uinfo: res.data.data,
                })
               
              }

            },
          })                 
  },
  beginSward(){
  var that = this;
  var id=wx.getStorageSync('wid');
  console.log(id)
  wx.request({
    url: repath + '?action=lucky.get.newyeardrawinfo',
    method: 'POST',
    header: { 'X-Requested-With': 'gzh' },
    data: {
      user_id:id,
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
          num1: t1,
          num2: t2,
          num3: t3,
          txt: txt
        }) 
      }, 1000)  
      if (status == 1) {
        var txt = '开始倒计时';
        that.setData({
          img_url: '../../image/greyegg.png',
          eflag: true,
          bflag: false,  
          flagt:false
        })
      }
      else if (status == 0) {
        var txt = '暂无活动';
        that.setData({
           flagt:true
        }) 
      }
      else if (status == 2) {
        var txt = '结束倒计时'
        that.setData({
          img_url: '../../image/goldegg.png',
          img_url1: '',
          eflag: false,
          bflag: true,
          flagt: false,
          animationData: {},
        })
      }
    },
  });   
}
})