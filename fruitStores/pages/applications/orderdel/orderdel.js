var app=getApp();
var repath=app.globalData.rePath;

var interval;
var varName;
var ctx = my.createCanvasContext('canvasArcCir');

Page({
  data: {
    items:[0,0,0,0,0],
    item1:[0,0],
    scale: 14,
    latitude: 114.3994900000,
    longitude: 30.5045600000,
   markers: [{
      iconPath: "/img/posm.png",
      id: 10,
      latitude: 114.3994900000,
      longitude: 30.5045600000,
      width: 50,
      height: 50
    }],
    includePoints: [{
      latitude: 114.3994900000,
      longitude: 30.5045600000,
    }],
    controls: [{
      id: 5,
      iconPath: '/img/pos.png',
      position: {
        left: 0,
        top: 300 - 50,
        width: 50,
        height: 50
      },
      clickable: true
    }]
  },
  onLoad(option) {
    var that = this;
    var a=new Date();
    var min=a.getMinutes();
    var hh=a.getHours();
    var sec=a.getSeconds();
    if(min<10){
      var min='0'+min;
    }
    if(hh<10){
      var hh='0'+hh;
    }
    this.setData({
      ostatus:option.ostatus,
      ossn2:option.ossn2,
      times:hh+':'+min,
    })
    console.log(option.ostatus);
    console.log(option.ossn2);
    my.getLocation({
      success(res) {
        my.hideLoading();
        console.log(res)
        that.setData({
          hasLocation: true,
          //location: formatLocation(res.longitude, res.latitude),
          longitude:res.longitude,
          latitude:res.latitude
        })
      },
      fail() {
        my.hideLoading();
        my.alert({ title: '定位失败' });
      },
    })
    this.orderInfo(); 
  },
  
  onShow() {
    this.showTime();
  },
  showTime(){
   var that=this;
    var i=1;
    var a=new Date();
    var min=a.getMinutes();
    var hh=a.getHours();
    var sec=a.getSeconds();
    if(min<10){
      var min='0'+min;
    }
    if(hh<10){
      var hh='0'+hh;
    }
    if(i){
      setInterval(function(){
        that.setData({
          times:hh+':'+min,
        })
        that.onShow();
      },60000)
    }
  },
  onReady(e) {
    // 使用 my.createMapContext 获取 map 上下文
    this.mapCtx = my.createMapContext('map');

    var cxt_arc = my.createCanvasContext('canvasArc');//创建并返回绘图上下文context对象。
       cxt_arc.setLineWidth(3);
       cxt_arc.setStrokeStyle('#d2d2d2');
       cxt_arc.setLineCap('round')
       cxt_arc.beginPath();//开始一个新的路径
       cxt_arc.arc(37, 37, 30, 0, 2*Math.PI,false);//设置一个原点(106,106)，半径为100的圆的路径到当前路径
       cxt_arc.stroke();//对当前路径进行描边
    
       cxt_arc.setLineWidth(3);
       cxt_arc.setStrokeStyle('#3ea6ff');
       cxt_arc.setLineCap('round')
       cxt_arc.beginPath();//开始一个新的路径
       cxt_arc.arc(37, 37, 30, 0, Math.PI*2*0.5,false);
       cxt_arc.stroke();//对当前路径进行描边
  
       cxt_arc.draw();

  },
  getCenterLocation() {
    this.mapCtx.getCenterLocation(function (res) {
      console.log(res.longitude)
      console.log(res.latitude)
    })
  },
 
  moveToLocation() {
    this.mapCtx.moveToLocation()
  },
  regionchange(e) {
    console.log('regionchange', e);
    // 注意：如果缩小或者放大了地图比例尺以后，请在 onRegionChange 函数中重新设置 data 的
    // scale 值，否则会出现拖动地图区域后，重新加载导致地图比例尺又变回缩放前的大小。
    if (e.type === 'end') {
      this.setData({
        scale: e.scale
      });
    }
  },
  markertap(e) {
    console.log('marker tap', e);
  },
  controltap(e) {
    console.log('control tap', e);
  },
  tap() {
    console.log('tap:');
  },
  changeScale() {
    this.setData({
      scale: 8,
    });
  },
  changeCenter() {
    this.setData({
      longitude: 113.324520,
      latitude: 23.199994,
      includePoints: [{
        latitude: 23.199994,
        longitude: 113.324520,
      }],
    });
  },
  changeMarkers() {
    this.setData({
      markers: [{
        iconPath: "/img/posm.png",
        id: 10,
        latitude: '114.3994900000',
      longitude: '30.5045600000',
        width: 50,
        height: 50
      }],
      includePoints: [{
        latitude: 21.21229,
        longitude: 113.324520,
      }],
    });
  },
  orderInfo(){
     var ossn2=this.data.ossn2; 
     var that=this;
     my.httpRequest({
     url:repath+"?action=order.get.detail",
     method: 'POST',
     data: {
      user_id:25,
      sn2:ossn2
    },
    dataType: 'json',
    success: function(res) {
      console.log(res.data);
      var tm=res.data.data.record.total_money;
      var fre=res.data.data.record.freight;
      if(fre){
        var fre=fre
      }
      else{
        var fre=0;
      }
      var totalprice=(parseFloat(tm)+parseFloat(fre)).toFixed(2);
      that.setData({
        odel:res.data.data,
        total:totalprice
      })
   },
  });
},
gomap(){
  my.navigateTo({
    url:'../map/map',
  });
},
returnCall(){
  my.makePhoneCall({ 
    number: '13986007724' ,
    seccess:function(res){
        console.log(res.data)
    }
  });
}
});
