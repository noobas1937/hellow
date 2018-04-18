var app=getApp();
var repath=app.globalData.rePath;

Page({
  data: {
    navs:[
      {name:'全部',id:0},
      {name:'待付款',id:1},
      {name:'待收货',id:2},
      {name:'待评价',id:3},
      {name:'退款',id:4}],
    orderStstus:0,
    flag:true,
    flag1:true,
    flag2:true
  },
  onLoad() {
    this.getAllItem();//全部订单
  },
  cancelOrder(){
    this.setData({
      flag:false
    });
   
  },
  cancelOrder1(){
    this.setData({
      flag:true
    })
  },
  cancelOrder2(){
     this.setData({
      flag:true
    })
  },
   contactRider1(){
    this.setData({
      flag1:true
    })
  },
  contactRider2(){
     this.setData({
      flag1:true
    })
  },
   returnMoney1(){
    this.setData({
      flag2:true
    })
  },
  returnMoney2(){
     this.setData({
      flag2:true
    })
  },
  orderdel(e){
    console.log(this.data.orderStstus);
    console.log(e.target.dataset.info);
    my.navigateTo({
      url: '../orderdel/orderdel?ostatus='+this.data.orderStstus+'&ossn2='+e.target.dataset.info
       })

  },
  getOrderInfo(event){
     var index=event.target.dataset.info;
     console.log(index);
     this.setData({
        orderStstus:index,
      })
      if(index==0){
        this.getAllItem();//全部订单
      }
      else if(index==1){
        this.waitPay();//待付款
      }
      else if(index==2){
        this.waitGetGoods();//待收货
      }
      else if(index==3){
        this.waitRate();//待评价
      }
      else if(index==4){
        this.waitRefund();//退款
      }
    },
 contactRider(){
    this.setData({
        flag1:false,
     })
    },
 hrOrder(){
    this.setData({
        flag2:false,
     })
    },
 getAllItem(){
    var that=this;
    my.httpRequest({
    url:repath+"?action=order.get.list",
    method: 'POST',
    data: {
      user_id:25,
      page:1,
      page_size:5,
   },
    dataType: 'json',
    success: function(res) {
      console.log(res.data);
      that.setData({
        allorder:res.data.data
      })
   },
  });
},
 waitPay(){
  var that=this;
    my.httpRequest({
    url:repath+"?action=order.get.status_list",
    method: 'POST',
    data: {
      user_id:25,
      status:0,
      page:1,
      page_size:5,
   },
    dataType: 'json',
    success: function(res) {
      console.log(res.data);
      that.setData({
        wo0:res.data.data
      })
   },
  });
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
 waitRate(){
   var that=this;
    my.httpRequest({
    url:repath+"?action=order.get.status_list",
    method: 'POST',
    data: {
      user_id:25,
      status:2,
      page:1,
      page_size:5,
   },
    dataType: 'json',
    success: function(res) {
      console.log(res.data);
      that.setData({
        wo2:res.data.data
      })
   },
  });
 },
 waitRefund(){
   var that=this;
    my.httpRequest({
    url:repath+"?action=order.get.status_list",
    method: 'POST',
    data: {
      user_id:25,
      status:3,
      page:1,
      page_size:5,
   },
    dataType: 'json',
    success: function(res) {
      console.log(res.data);
      that.setData({
        wo3:res.data.data
      })
   },
  });
},
onPullDownRefresh() {
    console.log('onPullDownRefresh', new Date())
}
});
