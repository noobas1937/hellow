var app=getApp();
var repath=app.globalData.rePath;

Page({
  data: {
     sid:0,
     navid:0,
     nav:['商品详情','商品评价'],
     scrollTop:100,
     flag:true,
     flag1:true,
     num:1,
  },
  onLoad(option) {
     console.log(option.itemid)
     var itemid=option.itemid;
     this.setData({
       itemid:itemid,
       cartnum:app.globalData.cartnum
     })
    var that=this;
    my.httpRequest({
    url:repath+"?action=item.detail.info",
    method: 'POST',
    data: {
      item_id:itemid
   },
    dataType: 'json',
    success: function(res) {
      console.log(res.data);
      var isubtitle=res.data.data.subtitle;
      console.log(isubtitle)
      var a=isubtitle.split('（');
      console.log(a);
      that.setData({
         itemdel:res.data.data,
         itemtit:a
      })
   },
  
 
  });
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
 cartNumAdd(){
    var num=this.data.num;
   
      num++;
   this.setData({
     num:num,
   })
 },
 cartNumSub(){
   var num=this.data.num;
   if(num>1){
      num--;
      this.setData({
        num:num,
        numstatus:false
      })
    }
    else{
       this.setData({
        num:1,
        numstatus:true
      })
    }
 },
  changSetMeal(e){
    var id=e.target.dataset.info;
    console.log(id);
    this.setData({
      sid:id
    })
  },
 navExchange(e){
    var id=e.target.dataset.info1;
    console.log(id);
    this.setData({
      navid:id
    })
  },
 onShareAppMessage() {
    return {
      title: '小程序示例',
      desc: '小程序官方示例Demo，展示已支持的接口能力及组件。',
      path: 'page/component/component-pages/view/view?param=123'
    };
  },
 scroll(event) {
    console.log(event.detail.scrollTop);
    var a=event.detail.scrollTop;
     if(a>400){
      this.setData({
         flag:false,
      })
    }
    else{
      this.setData({
         flag:true,
      })
    }
  },
 returnTop(){
    this.setData({
      scrollTop:0,
    })
  //this.onShow();
},
addCart(){
    var itemid=this.data.itemid;
    var num=this.data.num;
    var that=this;
    my.httpRequest({
    url:repath+"?action=item.cart.add",
    method: 'POST',
    data: {
      user_id:25,
      item_id:itemid,
      num:num
   },
    dataType: 'json',
    success: function(res) {
      console.log(res.data);
      that.onShow();
      that.setData({
         flag1:true,
      })
   },
  });
},
addCart1(){
   var itemid=this.data.itemid;
    var that=this;
    my.httpRequest({
    url:repath+"?action=item.cart.add",
    method: 'POST',
    data: {
      user_id:25,
      item_id:itemid,
      
   },
    dataType: 'json',
    success: function(res) {
      console.log(res.data);
     my.showToast({
     type: 'success',
     content: res.data.msg,
     duration: 3000,
     success: () => {
      that.onShow();
     },
    });
   },
  });
},
appendCart(){
  this.setData({
    flag1:false
  })
},
collect(){
  my.showToast({
  type: 'success',
  content: '收藏成功',
  duration: 3000,
  success: () => {
   
  },
});
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
