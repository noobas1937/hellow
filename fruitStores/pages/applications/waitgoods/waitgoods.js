var app=getApp();
var repath=app.globalData.rePath;
Page({
  data: {},
  onLoad() {
    this.waitGetGoods();
  },
  waitGetGoods(){
   var that=this;
    my.httpRequest({
    url:repath+"?action=order.get.status_list",
    method: 'POST',
    data: {
      user_id:25,
      status:1,
      page:1,
      page_size:5,
   },
    dataType: 'json',
    success: function(res) {
      console.log(res.data);
      that.setData({
        wo1:res.data.data
      })
   },
  });
 },
});
