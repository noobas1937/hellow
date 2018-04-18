Page({
  data: {
    titles:[
    '申请售后的方式有哪些？',
    '下订单后突然不想要可以退么？',
    '什么情况下可以退换货？',
    '什么情况下不可以退换货？',
    '退款什么时候可以到账？'],
    img1:"../../../img/bot.png",
    img2:"../../../img/top2.png",
  },
  onLoad() {},
  getDeail(e){
    var id=e.target.dataset.infos;
    console.log(id);
    this.setData({
      oid:id,
    })
  }
});
