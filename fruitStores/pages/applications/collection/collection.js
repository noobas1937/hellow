var app=getApp();
var repath=app.globalData.rePath;
Page({
  data: {},
  onLoad() {
     this.getHotList();//热卖
    },
    getHotList(){
   var that=this;
    my.httpRequest({
    url:repath+"?action=item.hot.list",
    method: 'POST',
    data: {
      page:1,
      page_size:5,
      city_id:2
   },
    dataType: 'json',
    success: function(res) {
      console.log(res.data);
      that.setData({
         hotitem:res.data.data,
      })
   },
  
 
  });
    },
});
