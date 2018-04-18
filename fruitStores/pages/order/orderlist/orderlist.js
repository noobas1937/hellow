var app=getApp();
var repath=app.globalData.rePath;
Page({
  data: {},
  onLoad() {
     my.getStorage({
      key: 'paydata', // 缓存数据的 key
      success: (res) => {
          console.log(res.data);
          this.setData({
             pay_id:res.data.pay_id,
             freight:res.data.freight
          })
      },
    });
    my.getStorage({
      key: 'cart', // 缓存数据的 key
      success: (res) => {
          console.log(res.data);
          this.setData({
            cart:res.data
          })
      },
    });
    
    var ds={
      user_id:25,
      pay_id:this.data.pay_id,
      cart:this.data.cart
    }
    var that=this;
    my.httpRequest({
     url:repath+"?action=order.confirm.detail",
     method: 'POST',
     data:ds ,
     dataType: 'json',
     success: function(res) {
       console.log(res.data);
    
       that.setData({
           mycart:res.data.data.cart,
          
          })
       console.log(res.data.data.cart)
     // that.onShow();

     
    
   },
  });
  },
});
