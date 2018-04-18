var app=getApp();
var repath=app.globalData.rePath;
Page({
  data: {
    time:[
      '10:30-11:00',
      '14:30-16:30',
      '19:30-21:30'
    ],
    mrtext:'请选择地址',
    flag1:true,
    ttime:'10:30-11:00'
  },
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
       var freight=that.data.freight;
       var tomtime=res.data.data.tom_time;
        var a=tomtime.split('年');
        console.log(1111); 
        console.log(a);     
       if(parseFloat(freight)){
           var totalPrice=(parseFloat(freight)+parseFloat(res.data.data.cart.priceSum)).toFixed(2);

          that.setData({
           mycart:res.data.data,
           totalPrice:totalPrice,
           tomtime:a[1]
          })
      }
      else{
         var totalPrice=(parseFloat(res.data.data.cart.priceSum)).toFixed(2);
         that.setData({
         mycart:res.data.data,
         totalPrice:totalPrice,
         tomtime:a[1]
       })
      }
      
     // that.onShow();

     
    
   },
  });


},
onShow(){
   my.getStorage({
      key: 'mraddress', // 缓存数据的 key
      success: (res) => {
          console.log(res.data);
          this.setData({
            msadd:res.data
          })
      },
    });
},
changeAddress(){
  my.navigateTo({
     url:'../../applications/maddress/maddress'
  });
},
goPay(){
  var that=this;
  var freight=this.data.freight;
  if(freight){
      freight=freight;
    }
  else{
    freight=0;
  }
  var memo=this.data.medo?this.data.medo:'have no message!'
  //var addid=this.data.msadd.id;
   if(!this.data.msadd){
      my.alert({
        title: '请选择地址', 
        success: (res) => {
          
        },
      });
    }
    else{
      var ds={
        user_id:25,
        memo:memo,
        money:this.data.mycart.cart.priceSum,
        pay_id:this.data.pay_id,
        is_package:this.data.mycart.is_package,
        delivery:this.data.ttime,
        delivery2:'',
        freight:freight,
        address_id1:this.data.msadd.id,
        cart:this.data.cart,
        time_slot1:this.data.ttime,
        }
   my.httpRequest({
     url:repath+"?action=order.add.pay",
     method: 'POST',
     data:ds,
     dataType: 'json',
     success: function(res) {
       console.log(res.data);
       that.setData({ 
       })
   },
  });
    }
  
},
goOrderList(){
   my.navigateTo({
     url:'../orderlist/orderlist'
  });
},
setTime(e){
 var id = e.target.dataset.info;
 console.log(id);
 console.log(this.data.time[id]);
 this.setData({
   tid:id,
   ttime:this.data.time[id],
   flag1:true
 })
},
psTime(){
  this.setData({
     flag1:false,
  })
},
getMessage(e){
  var a=e.detail.value;
  console.log(a);
  this.setData({
    medo:a
  })
}
});
