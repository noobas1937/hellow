Page({
  data: {
    flag:true,
    flag1:true,
    mheight:'height:70rpx;line-height:70rpx;border-top:3rpx solid #e72142;border-bottom:3rpx solid #e72142;padding:0 20rpx;'
  },
  onLoad() {},
  chooseSex(){
    this.setData({
       flag:false,
    })
  },
  getSexm(){
    this.setData({
      flag:true,
    })
  },
  getSexw(){
   this.setData({
      flag:true,
    })
  },
  goSetPhone(){
     my.navigateTo({
       url:'../set/setphone/setphone'
     });
  },
 birthChange(e){
   console.log(e.detail.value);
   this.setData({
      valuess: e.detail.value,
    });
  },
 getBirthDay(){
    var mvalue=this.data.valuess;
    var yys=this.data.yy[mvalue[0]];
    var mms=this.data.mm[mvalue[1]];
    var dds=this.data.dd[mvalue[2]];
    console.log(yys);
    console.log(mms);
    console.log(dds);
    this.setData({
      mbirth:yys+"-"+mms+"-"+dds,
      flag1:true
    })

 },
 setBirthDay(){
  var a=new Date();
  console.log(a);
  var ys=a.getFullYear();
  var yy=[];
  var mm=[];
  var dd=[];
  for(var i=ys;i>ys-100;i--){
      yy.push(i);
      //console.log(yy);
    }
  for(var j=1;j<13;j++){
   
    if(j<10){
      j="0"+j;
       mm.push(j);
      }
      else{
       mm.push(j)
      }
      //console.log(mm)
    }
  for(var m=1;m<32;m++){
   
    if(m<10){
      m="0"+m;
       dd.push(m);
      }
      else{
       dd.push(m)
      }
     // console.log(dd)
    }
    this.setData({
      yy:yy,
      mm:mm,
      dd:dd,
      flag1:false,
    })
}
});
