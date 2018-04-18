
App({
  onLaunch: function () {
    wx.getSystemInfo({
      success: function (res) {
        console.log(res);
      },
    })
  },
  onLoad:function(){
     
  },
  userInfo: null,
  getPhoneInfo(){
  },
  globalData:{
    userInfo:null,
    awardsConfig: {},
    runDegs: 0,
     //rePath:'https://open.connect-city.com.cn/apiv1',
      rePath:'http://ttfast.com/apiv1',
     //rePath:'http://test.api.nacy.cc/apiv1',
     // rePath: 'https://api.nacy.cc/apiv1',     
     //urlID: 'http://fast.com/apiv1?action=',
      oimg:'https://open.connect-city.com.cn/wxappimg/'
  }
})