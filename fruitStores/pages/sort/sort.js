var app=getApp();
var navd=app.globalData.navd;
var repath=app.globalData.rePath;
console.log(navd);
Page({
  data: {
    nav:[
      {id:0,name:'首页'},
      {id:1,name:'分类'},
      {id:2,name:'购物车'},
      {id:3,name:'我的'}
    ],
    navid:0,
    sortname:['新品','健康套餐','国产水果','进口水果'],
    itid:0
  },
  onLoad() {
      var navd=app.globalData.navd;
     console.log(app.globalData.navd);
    var navid=my.getStorageSync({key:'navid'});
    console.log(navid.data);
    this.setData({
      navid:navd,
    })
  },
  onShow(){
    this.getCartList();
    var cnum=app.globalData.cartnum;
    console.log(app.globalData.cartnum);
    if(cnum<1){
       this.setData({

       })
    }
    else{

    }
    this.setData({
      cartnum:cnum,
    })
  },
  changeBg(e){
    var id=e.target.dataset.info;
    app.globalData.navd=id;
    my.setStorageSync({
      key:'navid',
      data:id});
    console.log(id);
   
    if(id!=this.data.navid){
    if(id==0){
      my.redirectTo({
        url: '../home/home', // 需要跳转的应用内非 tabBar 的页面的路径，路径后可以带参数。参数与路径之间使用
      });
    }
    else if(id==1){
        my.redirectTo({
        url: '../sort/sort', // 需要跳转的应用内非 tabBar 的页面的路径，路径后可以带参数。参数与路径之间使用
      });
    }
    else if(id==2){
        my.redirectTo({
        url: '../cart/cart', // 需要跳转的应用内非 tabBar 的页面的路径，路径后可以带参数。参数与路径之间使用
      });
    }
     else if(id==3){
        my.redirectTo({
        url: '../application/application', // 需要跳转的应用内非 tabBar 的页面的路径，路径后可以带参数。参数与路径之间使用
      });
    }
  }
},
getSortItem(e){
  var id =e.target.dataset.inf;
  console.log(id);
  this.setData({
    itid:id,
  })
},
addCart(){
  
},
getCartList(){
    var that=this;
    my.httpRequest({
    url:repath+"?action=item.cart.list",
    method: 'POST',
    data: {
      user_id:25,
      page:1,
      page_size:5
   },
    dataType: 'json',
    success: function(res) {
      console.log(res.data);
      var a=res.data.data.length;
      app.globalData.cartnum=a;

      for(var i=0;i<res.data.data.length;i++){
           res.data.data[i].del_flag=true;
      }
      that.setData({
         cartlist:res.data.data,
         cartnum:app.globalData.cartnum
      })
   },
  });
},
});
