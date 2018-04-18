Page({
  data: {
    num:'获取验证码',
    flag1:false
  },
  onLoad() {},
  getIcode(){
    
         var i=10;
         var that=this;
    var yinterval=setInterval(function(){
        if(i>0){
            i--;
            that.setData({
              num:i+"S",
              flag1:true
            })
           
        }
        else if(i==0){
           that.setData({
              num:'重新获取',
              flag1:false
            })
          
           clearInterval(yinterval) 
        }
    },1000)  
  
  }
});
