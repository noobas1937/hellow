Page({
  data: {
    bgcolors:[
      {id:0,name:'mrh-4a2'},
      {id:1,name:'mrh-4a2'},
      {id:2,name:'mrh-4a2'},
      {id:3,name:'mrh-4a2'},
      {id:4,name:'mrh-4a2'}
    ],
    imgsurl:[]
  },
  onLoad() {},
  
  getRateScore(event){
    var index=event.target.dataset.info;
    console.log(index);
    var rs=this.data.bgcolors;
    for(var i=0;i<rs.length;i++){
        if(i<=index){
           rs[i].name="mrh-4a1"
          }
          else{
           rs[i].name="mrh-4a2"
          }
        }
     console.log(rs);
     this.setData({
       bgcolors:rs
     })
    },
getTextArea(e){
   console.log(e.detail.value)
  },
chooseImg(){
  var that=this;
  my.chooseImage({
    count: 2,
    success: (res) => {
      console.log(res)
    // img.src = res.apFilePaths[0];
    that.setData({
      imgsurl:res.apFilePaths,
    })
     },
    });//选择图片
  
  my.previewImage({
   current: 2,
   urls: that.data.imgsurl
   });//预览图片

  },
 fRate(){
    my.navigateTo({
     url: '../frate/frate'
    })
  }, 
});
