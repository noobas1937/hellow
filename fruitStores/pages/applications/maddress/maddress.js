var app=getApp();
var repath=app.globalData.rePath;
Page({
  data: {
     items: [
      {id:0,name: 'angular', value: 'AngularJS',disabled: true},
      {id:1,name: 'react', value: 'React',disabled: true},
      {id:2,name: 'polymer', value: 'Polymer',disabled: true},
      {id:3,name: 'vue', value: 'Vue.js',disabled: true},
      {id:4,name: 'ember', value: 'Ember.js',disabled: true},
      {id:5,name: 'backbone', value: 'Backbone.js', disabled: true},
    ],
  },
  onLoad() {
    this.getAddressList();
  },
  goBlance(event){
    var id=event.target.dataset.pri;
    console.log(id);
    for(var i=0;i<this.data.items.length;i++){
       if(id==i){
         this.data.items[i].disabled=false;
        }
        else{
         this.data.items[i].disabled=true;
        }
    }
    console.log(this.data.items);
    this.setData({
      items:this.data.items
    })
  },
  goNewAddress(){
    my.navigateTo({
     url: '../newaddress/newaddress'
    })
  },
  deleteAddress(event){
    var index=event.target.dataset.info;
    console.log(index);
    // var a=this.data.items;
     //a.splice(index,1);
    // var b=a;
    // console.log(b);
    // this.setData({
    //   items:b
    // })
     var that=this;
     my.httpRequest({
     url:repath+"?action=user.address.del",
     method: 'POST',
     data: {
       user_id:25,
       address_id:index
    },
     dataType: 'json',
     success: function(res) {
       console.log(res.data);
       that.onLoad();
     },
   });

    },
  getAddressList(){
    var that=this;
     my.httpRequest({
     url:repath+"?action=user.address.list",
     method: 'POST',
     data: {
       user_id:25,
       page:1,
       page_size:5
    },
     dataType: 'json',
     success: function(res) {
       console.log(res.data);
       var a=res.data.data;
       for(var i=0;i<a.length;i++){
           if(a[i].is_default==1){
              my.setStorage({
                key: 'mraddress', // 缓存数据的 key
                data: a[i], // 要缓存的数据
                success: (res) => {
                   console.log('缓存地址成功')
                },
              });
           }
       }
       that.setData({
          addlist:res.data.data
       })
   },
  });
},
setMyAddress(e){
  var id=e.target.dataset.info;
  console.log(id);
  my.setStorage({
     key: 'mraddress', // 缓存数据的 key
     data: this.data.addlist[id], // 要缓存的数据
     success: (res) => {
        console.log('缓存地址成功');
        my.navigateBack({
          delta: 1
        });
        },
      });
    },
updateAddress(e){
  var id=e.target.dataset.info;
  var addlist=this.data.addlist;
  console.log(id);
  my.setStorage({
    key: 'upaddress', // 缓存数据的 key
    data: addlist[id], // 要缓存的数据
    success: (res) => {
       my.navigateTo({
         url:'../newaddress/newaddress'
       });
    },
  });
}
});
