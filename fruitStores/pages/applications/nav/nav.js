Page({
  data: {
    nav:[
      {id:0,name:'首页'},
      {id:1,name:'分类'},
      {id:2,name:'购物车'},
      {id:3,name:'我的'}
    ],
    navid:0,
  },
  onLoad() {},
  changeBg(e){
    var id=e.target.dataset.info;
    my.setStorageSync({
      key:'navid',
      data:id});
    console.log(id);
    this.setData({
      navid:id,
    })
  }
});
