var app=getApp();
var repath=app.globalData.rePath;
var navd=app.globalData.navd;
console.log(navd);
Page({
  data: {
    item1:[0,0],
    items: [
      {id:0,name: 'angular', value: 'AngularJS',disabled: true},
      {id:1,name: 'react', value: 'React',disabled: true},
      {id:2,name: 'polymer', value: 'Polymer',disabled: true},
      {id:3,name: 'vue', value: 'Vue.js',disabled: true},
      {id:4,name: 'ember', value: 'Ember.js',disabled: true},
      {id:5,name: 'backbone', value: 'Backbone.js', disabled: true},
    ],
    allItem:true,
    num:1,
    //flag1:false,
     nav:[
      {id:0,name:'首页'},
      {id:1,name:'分类'},
      {id:2,name:'购物车'},
      {id:3,name:'我的'}
    ],
     navid:0,
     flags2:false,
     flags1:true,
     cartlist:[],
  },
  onLoad() {
      var navid=my.getStorageSync({key:'navid'});
       var navd=app.globalData.navd;
     console.log(app.globalData.navd);
      //console.log(navid.data);
      this.setData({
      navid:navd,
    })
    this.getCartList();
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
           if(res.data.data[i].number<10){
               res.data.data[i].number='0'+res.data.data[i].number;
              }
           else{
               res.data.data[i].number=res.data.data[i].number; 
              }
      }
      that.setData({
         cartlist:res.data.data,
         cartnum:app.globalData.cartnum
      })
   },
  });
},
delCartItem(e){
   var id=e.target.dataset.info;  
   console.log(id);
   var itemid=this.data.cartlist[id].item_id;
   console.log(itemid);
    var that=this;
    my.httpRequest({
    url:repath+"?action=item.cart.del",
    method: 'POST',
    data: {
      user_id:25,
      item_id:itemid
   },
    dataType: 'json',
    success: function(res) {
     that.onShow();

     
    
   },
  });

},
getTotalPrice(){
     var a=this.data.cartlist;
     console.log(a);
     var b=[];
     var m=0;
     var n=0;
     for(var i=0;i<a.length;i++){
         if(!a[i].del_flag){
           n++;
           console.log(a[i].number);
           console.log(parseFloat(a[i].item.price_original));
            var s=a[i].number*parseFloat(a[i].item.price_original);
            b.push(s);
         }
        }
        console.log(b);
        for(var j=0;j<b.length;j++){
           m+=parseFloat(b[j]);
          }
        console.log(n);
        console.log(m);
          this.setData({
             totalnum:n,
             totalprice:m.toFixed(2)
          })
},
  changeCart(){
      this.setData({
        flags1:false,
        flags2:true
      })
  },
  goBlance(e){
    var id=e.target.dataset.pri;
    var cartlist=this.data.cartlist;
   this.data.cartlist[id].del_flag=!this.data.cartlist[id].del_flag;
    this.setData({
       cartlist:this.data.cartlist,
    })
     
    
   
     // console.log(this.data.cartlist)
     this.getTotalPrice()
  },
  allSelected(){
    var idata=this.data.cartlist;
    this.data.allItem=!this.data.allItem;
    this.setData({
      allItem:this.data.allItem
    })
    console.log( this.data.allItem);
    for(var i=0;i<idata.length;i++){
      if(this.data.allItem){
        idata[i].del_flag=true;
      }
      else{
        idata[i].del_flag=false;
      }
        
    }
    
      console.log(idata);
      this.setData({
        cartlist:idata
      })
      this.getTotalPrice();
    
    },
    numSub(e){
      var id=e.target.dataset.info;
      this.data.cartlist[id].number;
      if(this.data.cartlist[id].number>1){
        this.data.cartlist[id].number--;
        this.setData({
          flag1:true,
        })
      }
      else{
        this.data.cartlist[id].number=1;
        this.setData({
          flag1:false,
        })
      }
      if(this.data.cartlist[id].number<10){
         this.data.cartlist[id].number='0'+ this.data.cartlist[id].number;
        }
      else{
        this.data.cartlist[id].number=this.data.cartlist[id].number;
        }
      this.setData({
        cartlist:this.data.cartlist
      })
      console.log(this.data.cartlist);
       this.getTotalPrice();
    },
    numAdd(e){
     var id=e.target.dataset.info;
      this.data.cartlist[id].number++;
      if(this.data.cartlist[id].number<10){
         this.data.cartlist[id].number='0'+ this.data.cartlist[id].number;
        }
      else{
        this.data.cartlist[id].number=this.data.cartlist[id].number;
      }
      
      this.setData({
        cartlist:this.data.cartlist,
        flag1:true,
      })
       this.getTotalPrice();
    },
    addCart(){
       
    },
    goMsOrder(){
      var as=this.data.cartlist;
      var pricetotal=this.data.totalprice;
      var jjs=[];
      var ds=[];
     
      var ds2=[];
      for(var i=0;i<as.length;i++){
        if(as[i].del_flag==false){
          jjs.push(as[i])
          
        }
         
        }
        
     for(var i=0;i<jjs.length;i++){
      var ds1={
          id:'',
          item_id:'',
          number:'',
          price_single:''};
          ds1.id=jjs[i].id;
          ds1.item_id=jjs[i].item_id;
          ds1.number=jjs[i].number;
          ds1.price_single=jjs[i].item.price_single;
          ds.push(ds1);
     }
       
    
        // console.log(ds);
      var sd={
        user_id:25,
        city_id:2,
        cart:{
          priceSum:pricetotal,
          shopping:'',
        }
       
      }
      //console.log(JSON.stringify(ds));
       sd.cart.shopping= ds;
       sd.cart=JSON.stringify(sd.cart);
       my.setStorage({
         key: 'cart', // 缓存数据的 key
         data: sd.cart, // 要缓存的数据
         success: (res) => {
            console.log("缓存购物车信息成功")
         },
       });
       console.log(sd);
       this.setData({
         sd:sd
       })
       var that=this;
      my.httpRequest({
      url:repath+"?action=order.confirm.pay_record",
      //header:{'Content-Type': 'application/x-www-form-urlencoded'},
      method: 'POST',
      data:that.data.sd,
      dataType: 'json',
      success: function(res) {
         console.log(res.data);
         my.setStorage({
           key: 'paydata', // 缓存数据的 key
           data: res.data.data, // 要缓存的数据
           success: (res) => {
             console.log('缓存成功')
               my.navigateTo({
               url:'../order/msorder/msorder'
             });
           },
         });
      
     
    
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
  }
});
