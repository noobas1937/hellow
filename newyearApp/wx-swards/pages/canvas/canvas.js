var app = getApp();
var repath=app.globalData.rePath;
Page({
  data: {
    awardsList: {},
    animationData: {},
    btnDisabled: '',
    jifen:200,
    animationInfo:{},
    flag1:true,
    flag2:true,
  },
  gotoList: function() {
    wx.switchTab({
      url: '../list/list',
    })
  },
  onLoad(){
    var animationRun = wx.createAnimation({
     duration: 4000,
      timingFunction: 'ease-in-out'
   })
   
    animationRun.rotate(0).step()
    this.setData({
      animationData: animationRun.export(),
      btnDisabled: 'disabled'
    })
     this.animationRun = animationRun
     wx.setStorageSync(
    'winAwards',
       [])
    },
  onShow(){
    this.getShowTimeInfo();
    if(wx.getStorageSync('wid')){
        var point=wx.getStorageSync('wid').point;
      this.setData({
         point:point,
      })
    }
    else{
       this.setData({
         point:0,
      })
    }
    if(wx.getStorageSync('avatarUrl'))  {
      var avatarUrl = wx.getStorageSync('avatarUrl');
      this.setData({
        avatarUrl: avatarUrl
      })
    }
  },
  getLottery: function () {
    this.setData({
      btnDisabled: 'disabled'
    })
     var that = this;
     var uid=wx.getStorageSync('wid').id;
      wx.request({
       url:repath+'?action=lucky.get.luckydraw',
       method: 'POST',
       data: {
       user_id:uid,
       draw_id: 1,
     },
    dataType: 'json',
    success: function(res) { 
      console.log(res.data.status)  
       if(res.data.status=='success'){
         
        var id=res.data.data?res.data.data.id:'';  
       var sw=app.awardsConfig.awards;
       for(var i=0;i<sw.length;i++){
          if(sw[i].id==id){  
    var le = app.awardsConfig.awards ? app.awardsConfig.awards.length:0;  
    var awardIndex = i;
     //获取奖品配置
    var awardsConfig = app.awardsConfig,
        runNum = le
    // 旋转抽奖
    app.runDegs = app.runDegs || 0
    app.runDegs = app.runDegs + (360 - app.runDegs % 360) + (360 * runNum - awardIndex * (360 / le))  
    that.animationRun.rotate(app.runDegs).step()
     that.setData({
      animationData: that.animationRun.export(),
      btnDisabled: 'disabled'
    })
     // 记录奖品
   //var winAwards = wx.getStorageSync('winAwards');
  // winAwards.winAwards.push(awardsConfig.awards[awardIndex].prize + '1个')
  // wx.setStorageSync('winAwards',winAwards.data.winAwards)

    // 中奖提示
     
    setTimeout(function() {
      
      if (awardsConfig.awards[awardIndex].prize == '谢谢参与') {
        var title = "谢谢参与", msg = '继续加油，好运总会来的';
        that.setData({
          awardsname: awardsConfig.awards[awardIndex].prize,
          flag2: false
        })

      } else {
        var title = "恭喜您", msg = '获得' + awardsConfig.awards[awardIndex].prize + "1个";
        that.setData({
          awardsname: awardsConfig.awards[awardIndex].prize,
          flag1: false
        })

      }
     
     
     
     if (awardsConfig.chance) {
        that.setData({
         btnDisabled: ''
       })  
      }
    }, 4000);
   
   
          }
        }
    that.getEmployeInfo();  
      }
  else{
     wx.showModal({
       title: '友情提示',
       content: res.data.msg,

       success: (res) => {
         that.setData({
           btnDisabled: ''
         })
         
       },
     })
   
  }
    
   },
  });  
    
   




    
   
   
  },

  onReady(){
    var uid=wx.getStorageSync('wid').id;
    var that = this;
   wx.request({
    url:repath+ '?action=lucky.get.luckydrawinfo',
    method: 'POST',
    data: {
    user_id:uid,
    draw_id: 1,
   },
   dataType: 'json',
   success: function(res) {
      console.log(res.data);
      app.awardsConfig = {
      chance: true,
      awards:res.data.data
    }
   
    // wx.setStorageSync('awardsConfig', JSON.stringify(awardsConfig))
    

    // 绘制转盘
    var awardsConfig = app.awardsConfig.awards,
      // console.log(awardsConfig)
      // console.log("查看我的字")
       len = awardsConfig.length,
        rotateDeg = 360 / len / 2 + 90,
        html = [],
        turnNum = 1 / len  // 文字旋转 turn 值
    that.setData({
      btnDisabled: app.awardsConfig.chance ? '' : 'disabled'  
    })
    var ctx = wx.createCanvasContext('lotteryCanvas')
     
    for (var i = 0; i < len; i++) {
      // 保存当前状态
      ctx.save();
      // 开始一条新路径
      ctx.beginPath();
      // 位移到圆心，下面需要围绕圆心旋转
      ctx.translate(150, 150);
      // 从(0, 0)坐标开始定义一条新的子路径
      ctx.moveTo(0, 0);
      // 旋转弧度,需将角度转换为弧度,使用 degrees * Math.PI/180 公式进行计算。
      ctx.rotate((360 / len * i - rotateDeg) * Math.PI/180);
      // 绘制圆弧
      ctx.arc(0, 0, 150, 0,  2 * Math.PI / len, false);

      // 颜色间隔
      if (i % 2 == 0) {
          ctx.setFillStyle('rgba(0,0,0,1)');
      }else{
          ctx.setFillStyle('rgba(255,255,255,1)');
      }

      // 填充扇形
      ctx.fill();
      // 绘制边框
      ctx.setLineWidth(0.5);
      ctx.setStrokeStyle('rgba(0,0,0,.1)');
     
      ctx.stroke();
         
      // 恢复前一个状态
      ctx.restore();


     

      ctx.draw();

       
      // 奖项列表
      html.push({turn: i * turnNum + 'turn', lineTurn: i * turnNum + turnNum / 2 + 'turn', award: awardsConfig[i].prize});    
    }

      

    that.setData({
        awardsList: html
      });
    console.log(that.data.awardsList);
   
   },
   fail: function(res) {
    
   },
   complete: function(res) {
    
   }
 });
   

  },
 

gotoList(){
  wx.navigateTo({
    url: '../list/list',
  })
},
getEmployeInfo(){
  var uid=wx.getStorageSync('uid');
  var mobile=wx.getStorageSync('mymobile1');
  var that=this;
  wx.request({
         url:repath+'?action=user.get.employee',
         method: 'POST',
         data: {
           user_id:uid,
           mobile:mobile
        },
         dataType: 'json',
        success: function(res) {
          console.log(res.data);
        
          wx.setStorageSync(
                 'wid',
                {id:res.data.data.id,point:res.data.data.points}
               );
        
           
          that.onShow()
         
    },
    });
  },
closeMySwardsInfo(){
   this.setData({
     flag1:true,
     flag2:true,
   })
},
getShowTimeInfo(){
  var that = this;
  var uid = wx.getStorageSync('wid').id;
  wx.request({
    url: repath + '?action=lucky.get.luckdrawprizerecord',
    method: 'GET',
    data: {
      user_id: uid,
      draw_id: 1,
    },
    dataType: 'json',
    success: function (res) {
       that.setData({
         swd:res.data.data
       })
      console.log(res.data);
     setInterval(function(){
       //that.onShow();
     },10000)
     

     

    },
  });
},
getAgain(){
  this.setData({
    flag1:true,
    flag2:true,
  })
},
findMySwards(){
 wx.navigateTo({
   url: '../list/list',
   success:function(res){
     this.setData({
       flag1:true,
     })
   }
 })
}
})
