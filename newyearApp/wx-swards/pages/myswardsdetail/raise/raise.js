var app = getApp();
var repath = app.globalData.rePath;
Page({
  data: {
    flag1:true,
    flag2:true
  },
  onLoad(option) {
    var sid = option.sid;
    console.log(sid)
    this.historyList(sid);
  },
  getidx(e){
    var idx=e.currentTarget.dataset.info;
    console.log(idx);
    this.setData({
       ids:idx,
    })
  },
  rnGetSward(){
    this.setData({
      flag1:false
    })
  },
  onSureUse(){
     this.setData({
      flag1:true
    })
  },
 findSwardsNumber(){
    this.setData({
      flag2:false
    })
  },
 closeMyNum(){
    this.setData({
      flag2:true
    })
 },
 historyList(sid) {
   var that = this;
   
     var uid = wx.getStorageSync('wid');
     wx.request({
       url: repath + '?action=lucky.get.luckyapplyinfo',
       method: 'POST',
       header: { 'X-Requested-With': 'gzh' },
       data: {
         user_id: uid,
         draw_id: sid
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
 goMyluckyNumber() {
   var id = this.data.glswd.draw.id;
   wx.navigateTo({
     url: '../../applications/myluckynumber/myluckynumber?id=' + id,
   })
 }
});
