var app = getApp();
var rePath = app.globalData.rePath;

Page({
  data: {
    flag1: true,
    flag2: true,
    snum:[1,10,20],
    nid:1,
    modalshow:true,
    flags:true,
    mid:[],
    sid:'',
    cost:'',
    left:''
  },
  onLoad(option) { 
    console.log(option.sid);
    var sid = option.sid;
    var date = new Date();
    var time3 = Date.parse(date);
    this.setData({
       sid:sid,
       timestr: time3 / 1000,
    })
  },
  onShow(){
    var sid=this.data.sid;
    this.getSwardsDetail(sid);
  },
  getidx(e) {
    var idx = e.currentTarget.dataset.info;
    var unum = this.data.snum[idx];
    var scorenum = this.data.swdel.draw.credits;
    var uscore = this.data.snum[idx] * scorenum;
    console.log(idx);
    this.setData({
      ids: idx,
      uscore: uscore,
      unum: unum
    })
  },
  rnGetSward() {
    var points = this.data.swdel.draw.credits;
    var nid = this.data.nid;
    this.setData({
      flag1: false,
      nids: points*nid
    })
  },
  closePay(){
    this.setData({
       flag1:true
    })
  },
  findSwardsNumber() {
    this.setData({
      flag2: false
    })
  },
  closeMyNum() {
    this.setData({
      flag2: true
    })
  },
 getSwardsDetail(sid){
   var that = this;
     var uid = wx.getStorageSync('wid');
     console.log(uid)
     wx.request({
       url: rePath + "?action=lucky.get.luckyapplyinfo",
       method: 'POST',
       header: { 'X-Requested-With': 'gzh' },
       data: {
         user_id: uid,
         draw_id: sid, 
       },
       dataType: 'json',
       success: function (res) {
         console.log(res.data);
         var s = res.data.data.ticketnumber;
         console.log(s);
         var arr=[];
         for (var i = 0; i < s; i++) {
           arr[i]=false;
         }
         that.setData({
           swdel: res.data.data,
           cost:res.data.data.draw.credits,
           left: res.data.data.points - res.data.data.draw.credits,
           mid:arr,
         })
       },
     })
   
  
},
onSureUse(){
   
 
},

getNumInput(e){
  var id = e.detail.value;
  if (id && id > 0) {
    this.setData({
      nid: id
    })

  }
  var j = 0;
  for (var i = 0; i < this.data.mid.length; i++) {
    if (this.data.mid[i] == true) {
      j++;
    }
  }
  var that = this;
  var uid = wx.getStorageSync('wid');
  var sid = this.data.sid;
  var nid = this.data.nid;
  
  wx.request({
    url: rePath + "?action=lucky.get.luckyparam",
    method: 'POST',
    header: { 'X-Requested-With': 'gzh' },
    data: {
      user_id: uid,
      draw_id: sid,
      number: nid,
      ticketnumber: j
    },
    dataType: 'json',
    success: function (res) {
      console.log(res.data);
      if (res.data.code == 3) {
        that.setData({
          cost: res.data.data.cost,
          left: res.data.data.left
        })
      } else if (res.data.code == 1122) {
        that.setData({
          modalshow: false,
        })
      } else {
        wx.showToast({
          title: res.data.msg,
        })
      }

    },
  })

  
  },
getNumShare(e){
   var id=e.detail.value;
   if(id&&id>0){
      this.setData({
        nid:id,
      })
   }
},
sub(){
  var j = 0;
  for (var i = 0; i < this.data.mid.length; i++) {
    if (this.data.mid[i] == true) {
      j++;
    }
  }
  var that = this;
  var uid = wx.getStorageSync('wid');
  var sid=this.data.sid;
  var nid=this.data.nid;
   if(nid>1){
     nid--;
   }
   else{
     nid=1
   }
   wx.request({
     url: rePath + "?action=lucky.get.luckyparam",
     method: 'POST',
     header: { 'X-Requested-With': 'gzh' },
     data: {
       user_id: uid,
       draw_id: sid,
       number:nid,
       ticketnumber: j
     },
     dataType: 'json',
     success: function (res) {
       console.log(res.data);
       if(res.data.code==3){
           that.setData({
             cost:res.data.data.cost,
             left:res.data.data.left
           })
       }else if(res.data.code==1122){
            that.setData({
              modalshow: false,
            })
       }else{
            wx.showToast({
              title: res.data.msg,
            })
       }
      
     },
   })

   this.setData({
     nid:nid,
    
   })
},
add() {
  var j = 0;
  for (var i = 0; i < this.data.mid.length; i++) {
    if (this.data.mid[i] == true) {
      j++;
    }
  }
  console.log(this.data.mid)
  var nid = this.data.nid;
  var that = this;
  var uid = wx.getStorageSync('wid');
  var sid = this.data.sid;
  nid++;
  wx.request({
    url: rePath + "?action=lucky.get.luckyparam",
    method: 'POST',
    header: { 'X-Requested-With': 'gzh' },
    data: {
      user_id: uid,
      draw_id: sid,
      number: nid,
      ticketnumber: j
    },
    dataType: 'json',
    success: function (res) {
      console.log(res.data);
      if (res.data.code == 3) {
        that.setData({
          cost: res.data.data.cost,
          left: res.data.data.left
        })
      } else if (res.data.code == 1122) {
        that.setData({
          modalshow: false,
        })
      } else {
        wx.showToast({
          title: res.data.msg,
        })
      }
    },
  })
 
  this.setData({
    nid: nid,
    
  })
},
goMyluckyNumber() {
  var id = this.data.swdel.draw.id
    wx.navigateTo({
      url: '../applications/myluckynumber/myluckynumber?id='+id,
    })
  },
thinkMore(){
  this.setData({
    flag1:true,
  })
},
msMyPayment(){
  var nid = this.data.nid;
  var that = this;
  var uid = wx.getStorageSync('wid');
  var sid = this.data.sid;
  
  var j = 0;
  for (var i = 0; i < this.data.mid.length; i++) {
    if (this.data.mid[i] == true) {
      j++;
    }
  }
  wx.request({
    url: rePath + "?action=lucky.get.newluckyapply",
    method: 'POST',
    header: { 'X-Requested-With': 'gzh' },
    data: {
      user_id: uid,
      draw_id: sid,
      number: nid,
      ticketnumber:j
    },
    dataType: 'json',
    success: function (res) {
      console.log(res.data);
      if (res.data.code == 3) {
        that.setData({
          flag1:true
        })
        that.onShow();
      } else if (res.data.code == 1122) {
        that.setData({
          modalshow: false,
        })
      } else {
        wx.showToast({
          title: res.data.msg,
        })
      }
    },
  })

},
goHello: function () {
  wx.redirectTo({
    url: '/pages/guide/guide',
  })
},
goIdentity: function () {
  wx.redirectTo({
    url: '/pages/identity/identity',
  })
},
selectMyCoupon(e){
 
  var id = e.currentTarget.dataset.info;
  var nid = this.data.nid;
  var that = this;
  var uid = wx.getStorageSync('wid');
  var sid = this.data.sid;
   this.data.mid[id]=!this.data.mid[id];
   this.setData({
     mid:this.data.mid
   })

   var j = 0;
   for (var i = 0; i < this.data.mid.length; i++) {
     if (this.data.mid[i] == true) {
       j++;
     }
   }

   wx.request({
     url: rePath + "?action=lucky.get.luckyparam",
     method: 'POST',
     header: { 'X-Requested-With': 'gzh' },
     data: {
       user_id: uid,
       draw_id: sid,
       number: nid,
       ticketnumber: j
     },
     dataType: 'json',
     success: function (res) {
       console.log(res.data);
       if (res.data.code == 3) {
         that.setData({
           cost: res.data.data.cost,
           left: res.data.data.left,
           modalshow: true,
         })
       } else if (res.data.code == 1122) {
         that.setData({
           modalshow: false,
         })
       } else {
         wx.showToast({
           title: res.data.msg,
         })
       }
     },
   })
}
});
