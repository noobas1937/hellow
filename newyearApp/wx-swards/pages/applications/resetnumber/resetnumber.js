var app = getApp();
var repath = app.globalData.rePath;
var yinterval;
Page({
  data: {
    num: '获取验证码',
    flag1: false,
    flas: true,
    flag3: true,
  },
  onLoad() {
    this.getUserInfos();
  },
  getUserInfos() {
    if (wx.getStorageSync('uinfo')) {
      var sin = wx.getStorageSync('uinfo');
      this.setData({
        celnumber: sin.mobile,
        uname: sin.uname
      })
    }
  },
  getIcode() {
    //console.log(1111);
    var celnumber = this.data.celnumber;
    console.log(celnumber)
    var i = 60;
    var that = this;
    var ms = /^1[3|4|5|7|8][0-9]\d{8}$/;
    if (!ms.test(celnumber)) {
      wx.showToast({
        title: '输入有误',
      })
      that.setData({
        flag1: false
      })
    }
    else {
      wx.request({
        url: repath + "?action=user.post.telcode",
        method: 'POST',
        header: { 'X-Requested-With': 'gzh' },
        data: {
          mobile: celnumber
        },
        dataType: 'json',
        success: function (res) {
          console.log(res.data);
        },
      });
      clearInterval(yinterval);
      var yinterval = setInterval(function () {
        if (i > 0) {
          i--;
          that.setData({
            num: i + "S",
            flag1: true
          })
        }
        else if (i == 0) {
          that.setData({
            num: '重新获取',
            flag1: false
          })
        }
      }, 1000)
    }
  },
  getCode(e) {
    var code = e.detail.value;
    this.setData({
      code: code,
    })
  },
  setMobile() {
    var code = this.data.code;
    var mobile = this.data.celnumber;
    var userid = wx.getStorageSync('wid');
    var that = this;
    wx.request({
      url: repath + "?action=user.post.setmobile",
      method: 'POST',
      header: { 'X-Requested-With': 'gzh' },
      data: {
        code: code,
        mobile: mobile,
        user_id: userid
      },
      dataType: 'json',
      success: function (res) {
        console.log(res.data);
        if (res.data.code == 3) {
          wx.showModal({
            title: '友情提示',
            content: res.data.msg,
            success: (res) => {
               wx.switchTab({
                 url: '../../test/test',
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
  getCelNumer(e) {
    var num = e.detail.value;
    console.log(num);
    this.setData({
      celnumber: num
    })
  },
  close() {
    this.setData({
      flag3: true,
    })
    wx.switchTab({
      url: '../test/test',
    })
  }
});
