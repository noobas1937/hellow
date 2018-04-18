var app = getApp();
var repath = app.globalData.rePath;
Page({
  data: {
    showr:true,
  },
  onLoad() {
    var avatarUrl=wx.getStorageSync('avatarUrl');
  },
  onShow(){
    this.getMySwards();
  },
  goRaise(e){
    var sid = e.currentTarget.dataset.info;
    wx.navigateTo({
      url: '../goodluckdetail/goodluckdetail?sid=' + sid,
    });
  },
 noPriceDetail(e){
   var sid = e.currentTarget.dataset.info;
    wx.navigateTo({
      url: '../myswardsdetail/noprize/noprize?sid=' + sid,
    });
  },
waitPrizeDetail(e){
  var sid = e.currentTarget.dataset.info;
  wx.navigateTo({
    url: '../myswardsdetail/waitprize/waitprize?sid=' + sid,
    });
},
getPrizeDetail(e){
  var sid=e.currentTarget.dataset.info;
  console.log(e);
  console.log(sid);
  wx.navigateTo({
    url: '../myswardsdetail/getprize/getprize?sid='+sid,
  });
},
goFailPrize(e){
  var sid = e.currentTarget.dataset.info;
  console.log(e);
  console.log(sid);
  wx.navigateTo({
    url: '../myswardsdetail/failprize/failprize?sid=' + sid,
  });
},
getMySwards() {
  var that = this;
  
    var uid = wx.getStorageSync('wid');
    wx.request({
      url: repath + '?action=lucky.get.luckyapplyrecord',
      method: 'GET',
      header: { 'X-Requested-With': 'gzh' },
      data: {
         user_id:uid,
      },
      dataType: 'json',
      success: function (res) {
        if(res.data.code==3){
          if (res.data.data.length>0){
            that.setData({
              glswd: res.data.data,
              showr: true,
            })
          }
          else{
             that.setData({
               showr:false
             })
          }
          
        } else{
          wx.showModal({
            title: '友情提示',
            content: res.data.msg,
          })
        }
       
      },
    });
 
  
}, 
});
