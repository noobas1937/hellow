var app=getApp();
var repath=app.globalData.rePath;
var cnum=app.globalData.cartnum;
var navd=app.globalData.navd;

Page({
  data: {
    background: ['green', 'red', 'yellow'],
    indicatorDots: false,
    autoplay: false,
    interval: 3000,
    itemss:[0,0,0,0,0],
     nav:[
      {id:0,name:'首页'},
      {id:1,name:'分类'},
      {id:2,name:'购物车'},
      {id:3,name:'我的'}
    ],
    navid:0,
    saddress:[
      {
        name:'武汉',
        province_id:1,
        city_id:2
      },
       {
        name:'黄石',
        province_id:1,
        city_id:178
      },
    ],
    flagd:false
  },
  onLoad(){
    //this.getUserCode();//获取用户code
    this.getNavId();//获取导航条下标
    //this.gitBanner(); //广告位，首页轮播图
    //this.gitBanner1();//广告位，新客和特惠
    //this.gitBanner2();//广告位，新品首发
    //this.gitBaseNews();//头条新闻
    //this.getHotList();//热卖
   // this.getNewsList();//最新
    //this.getClassList();//商品推荐
     //this.getHomeInfo();
     my.setStorage({
       key: 'user_id',
       data: 25, 
       
     });
     var navd=app.globalData.navd;
     console.log(app.globalData.navd);
     this.setData({
      navid:navd,
    });
    var that=this;
    my.getStorage({
      key: 'cityid', // 缓存数据的 key
      success: (res) => {
        console.log(res.data);
         that.setData({
           cityid:res.dataid,
          })
          if(res.data){
           that.getHomeInfo();
           that.setData({
              flagd:true,
              cityname:res.data.name
            })
             }
         else{
            that.setData({
             flagd:false
           })
       }
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
        cartnum:cnum
     })
    },
  getNavId(){
   
  },
  getUserCode(){
     my.getAuthCode({
      scopes: 'auth_user',
      success: (res) => {
     my.alert({
	   content: res.authCode,
	   });
     },
    });
  },
  addCart(e){
   //app.globalData.cartnum++;
   //this.onShow();
   var id =e.target.dataset.info1;
   console.log(id);
    var that=this;
    my.httpRequest({
    url:repath+"?action=item.cart.add",
    method: 'POST',
    data: {
      user_id:25,
      item_id:id,
   },
    dataType: 'json',
    success: function(res) {
      console.log(res.data);
     that.onShow();
        
   },
  });
  },
  changeIndicatorDots(e) {
    this.setData({
      indicatorDots: !this.data.indicatorDots
    })
  },
  changeAutoplay(e) {
    this.setData({
      autoplay: !this.data.autoplay
    })
  },
  intervalChange(e) {
    this.setData({
      interval: e.detail.value
    })
  },
  goItemDel(e){
    var id = e.target.dataset.info;
    console.log(id)
     my.navigateTo({
        url: '../items/items?itemid='+id, 
      });
    },
  gotext(){
    my.navigateTo({
        url: '../text/text', 
      });
    },
  goSearch(){
    my.navigateTo({
      url: '../search/search', 
     });
   //console.log(repath);
   
},
getAdList(){

 },
getClassList(){
    var that=this;
    my.httpRequest({
    url:repath+"?action=item.class.list",
    method: 'POST',
    data: {
      
      page:1,
      page_size:5,
   },
    dataType: 'json',
    success: function(res) {
      console.log(res.data);
      that.setData({
         classitem:res.data.data,
      })
   },
  });
  },
getNewsList(){

   },
getHotList(cid){
   var that=this;
    my.httpRequest({
    url:repath+"?action=item.hot.list",
    method: 'POST',
    data: {
      page:1,
      page_size:5,
      city_id:cid
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
getNewsList(cid){
   var that=this;
    my.httpRequest({
    url:repath+"?action=item.newest.list",
    method: 'POST',
    data: {
      page:1,
      page_size:3,
      city_id:cid
   },
    dataType: 'json',
    success: function(res) {
      console.log(res.data);
      that.setData({
         newitem:res.data.data,
      })
   },
  
 
  });
},
gitBanner(cid){
 var that=this;
    my.httpRequest({
    url:repath+"?action=base.ad.list",
    method: 'POST',
    data: {
      type:1,
      page:1,
      page_size:5,
      city_id:cid
   },
    dataType: 'json',
    success: function(res) {
      console.log(res.data.data);
      that.setData({
         newitem1:res.data.data,
      })
   },
  });
},
gitBanner1(cid){
 var that=this;
    my.httpRequest({
    url:repath+"?action=base.ad.list",
    method: 'POST',
    data: {
      type:2,
      page:1,
      page_size:2,
      city_id:cid
   },
    dataType: 'json',
    success: function(res) {
      console.log(res.data);
      that.setData({
         newitem2:res.data.data,
      })
   },
  });
},
gitBanner2(cid){
 var that=this;
    my.httpRequest({
    url:repath+"?action=base.ad.list",
    method: 'POST',
    data: {
      type:4,
      page:1,
      page_size:1,
      city_id:cid
   },
    dataType: 'json',
    success: function(res) {
      console.log(res.data);
      that.setData({
         newitem3:res.data.data,
      })
   },
  });
},
gitBaseNews(cid){
 var that=this;
    my.httpRequest({
    url:repath+"?action=base.news.list",
    method: 'POST',
    data: {
      page:1,
      page_size:1,
      city_id:cid
   },
    dataType: 'json',
    success: function(res) {
      console.log(res.data);
      console.log(1111111111111111111)
      that.setData({
         newitem4:res.data.data,
      })
   },
  });
},
goNewestItem(e){
   var id =e.target.dataset.info;
   console.log(id);
   my.navigateTo({
        url: '../items/items?itemid='+id, 
      });

    },
goBannerItem(e){
  var id =e.target.dataset.info;
  console.log(id)
   
   my.navigateTo({
       url: '../items/items?itemid='+id, 
      });
},
getNewIndex(e){
  //console.log(e.detail.value)
},
toNewCustom(i){
 console.log(i)
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
        url: '../home/home', 
      });
    }
    else if(id==1){
        my.redirectTo({
        url: '../sort/sort',
      });
    }
    else if(id==2){
        my.redirectTo({
        url: '../cart/cart', 
      });
    }
     else if(id==3){
        my.redirectTo({
        url: '../application/application', 
      });
    }
  }
},
selectCity(e){
  var that=this;
  var id=e.target.dataset.info;
  var add=this.data.saddress;
  console.log(add[id]);
   my.httpRequest({
    url:repath+"?action=data.set.user_status",
    method: 'POST',
    data: {
      user_id:25,
      province_id:add[id].province_id,
      city_id:add[id].city_id
   },
    dataType: 'json',
    success: function(res) {
      console.log(res.data);
       my.setStorage({
         key: 'cityid', 
         data:{id:add[id].city_id,name:add[id].name}, // 要缓存的数据
         success: (res) => {
             console.log(res);
         },
       });
       that.setData({
         flagd:true,
         maddresss:add[id].name
       })
       that.onLoad();
   },
  });
},
getHomeInfo(){
  var that=this;
  my.getStorage({
    key: 'cityid', 
    success: (res) => {
      console.log(res.data);
      var city_id=res.data.id;
      this.getUserCode();//获取用户code
    //this.getNavId();//获取导航条下标
      that.gitBanner(city_id); //广告位，首页轮播图
      that.gitBanner1(city_id);//广告位，新客和特惠
      that.gitBanner2(city_id);//广告位，新品首发
      that.gitBaseNews(city_id);//头条新闻
      that.getHotList(city_id);//热卖
      that.getNewsList(city_id);//最新
      that.getClassList(city_id);//商品推荐
    },
  });
}
})