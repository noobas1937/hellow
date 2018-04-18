var app = getApp();
var repath = app.globalData.rePath;

Page({
  data: {},
  onLoad() {
   
  },
  onShow(){
    var that=this;
    wx.getUserInfo({
      success: function (res) {
        that.setData({
          avatarUrl: res.userInfo.avatarUrl,
        })
      },
    })
    this.getEmployeInfo();
    this.getApplicationInfo()
  },
  getAwardInfo(){
   var uid=wx.getStorageSync('wid').id;
   var that=this;
   my.request({
         url:rePath+'?action=lucky.get.luckyapplyinfo',
         method: 'POST',
         header: { 'X-Requested-With': 'gzh' },
         data: {
           user_id:uid,
           draw_id:2,
        },
         dataType: 'json',
        success: function(res) {
          console.log(res.data);  
           that.setData({
            ei:res.data.data,
          }) 

    },
    });
  },
  toMySwards(){
   
    wx.navigateTo({
       url: '../myswards/myswards', 
    });
  },
  toMySwards1() {

    wx.navigateTo({
      url: '../allhistory/allhistory',
    });
  },
  getEmployeInfo() {
    var uid = wx.getStorageSync('wid');
    var that = this;
    wx.request({
      url: repath + '?action=user.get.employeeinfo',
      method: 'POST',
      header: { 'X-Requested-With': 'gzh' },
      data: {
        user_id: uid,
      },
      dataType: 'json',
      success: function (res) {
        if(res.data.status=='success'){
          console.log(res.data);
          that.setData({
            point: res.data.data.points,
            mobile: res.data.data.contact_moblie,
            name: res.data.data.name,
            site: res.data.data.site,
            des: res.data.data.describe,
          })
         
        }
        else {
          wx.showModal({
            title: '提示',
            content: res.data.msg,
          })
        }
        


        

      },
    });
  },
  getApplicationInfo() {
    var uid = wx.getStorageSync('wid');
  
      var that = this;
      wx.request({
        url: repath + '?action=user.get.usercenter',
        method: 'GET',
        header: { 'X-Requested-With': 'gzh' },
        data: {
          user_id: uid,
        },
        dataType: 'json',
        success: function (res) {
          console.log(res.data);
          if(res.data.status="success"){
            that.setData({
              appinfo: res.data.data
            })
          }
          
        },
      });
   
    },
   
  goMyWages(){
    wx.navigateTo({
      url: '../applications/mywages/mywages',
    })

    
  },
  goMyIntegration(){
    wx.navigateTo({
      url: '../applications/myintegration/myintegration',
    })
  },
  goMyDesposit(){
    wx.navigateTo({
      url: '../applications/wdeposit/wdeposit',
    })
  },
  goMyReward(){
    wx.navigateTo({
      url: '../applications/myreward/myreward',
    })
  },
  goMyPersonInfo(){
    wx.navigateTo({
      url: '../applications/mypersoninfo/mypersoninfo',
    })
  },
  usuProblem(){
    wx.navigateTo({
      url: '../applications/usproblem/usproblem',
    })
  }
});
