Page({
  data: {
    scale: 14,
    longitude: 120.131441,
    latitude: 30.279383,
    markers: [{
      iconPath: "/img/posm.png",
      id: 10,
      latitude: '30.279383',
      longitude: '120.131441',
      width: 50,
      height: 50
    }],
    includePoints: [{
      latitude: 30.279383,
      longitude: 120.131441,
    }],
    
  },
  
  onReady(e) {
    // 使用 my.createMapContext 获取 map 上下文
    //this.mapCtx = my.createMapContext('map')
  },
  
  getCenterLocation() {
    //this.mapCtx.getCenterLocation(function (res) {
      console.log(res.longitude)
      console.log(res.latitude)
    //})
  },
  
  moveToLocation() {
    //this.mapCtx.moveToLocation()
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
        iconPath: "/image/green_tri.png",
        id: 10,
        latitude: 21.21229,
        longitude: 113.324520,
        width: 50,
        height: 50
      }],
      includePoints: [{
        latitude: 21.21229,
        longitude: 113.324520,
      }],
    });
  },
})