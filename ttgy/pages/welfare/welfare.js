var util=require("../../utils/util.js");
var init = require("../../components/footer/footer.js");
var app = getApp()
Page({
  data: {
    target:0,
    awardsList: {},
    animationData: {},
    btnDisabled: '',
    width:0,
    height:0,
    awards: [],
    activity_rule: [
      {
        id: 1,
        text: 'XXXXXXXXXXXX'
      },
      {
        id: 2,
        text: 'XXXXXXXXXXXX'
      },
      {
        id: 3,
        text: 'XXXXXXXXXXXX'
      }
    ],
    join: 10  //每次参加活动消耗的积分
  },
  onLoad: function (options) {
    var that = this;
    init.init(that);

    //获取系统信息  
    wx.getSystemInfo({
      //获取系统信息成功，将系统窗口的宽高赋给页面的宽高  
      success: function (res) {
        that.width = res.windowWidth
        // console.log(that.width)   375
        that.height = res.windowHeight
        // console.log(that.height)  625
        // 这里的单位是PX，实际的手机屏幕有一个Dpr，这里选择iphone，默认Dpr是2
        that.setData({
          width: res.windowWidth,
          height: res.windowHeight
        })
      }
    })
    // that.setData({
    //   points: 6000,
    // });
    app.getUserInfo(function (userInfo) {
      that.setData({
        userInfo: userInfo
      })
    });
    wx.getStorage({
      key: 'user_id',
      success: function (res) {
        that.getUser(res.data)
      },
      fail: function () {
        that.userLogin();
      }
    })
  },
  onReady: function (e) {

  },
  onShow: function (e) {
    var that = this;
    that.drawClock();
    // wx.getStorage({
    //   key: 'user_id',
    //   success: function (res) {
    //     that.getUser(res.data)
    //   },
    //   fail: function () {
    //     that.userLogin();
    //   }
    // })
  },
  /**
   * 用户登录
   */
  userLogin: function () {
    var that = this;
    wx.login({
      success: res => {
        // 发送 res.code 到后台换取 openId, sessionKey, unionId
        var code = res.code;
        wx.request({
          url: "https://api.weixin.qq.com/sns/jscode2session",
          method: "GET",
          data: {
            appid: 'wx5313a9e9e1ba4b5d',
            secret: '683f6fbd6e3c768fe10547e59b6cbbaf',
            grant_type: 'authorization_code',
            js_code: code
          },
          header: {
            "Content-Type": "application/json"
          },
          success: function (res) {
            // console.log(res.data);
            if (res.data.openid != null && res.data.openid != undefined) {
              var openid = res.data.openid;
              wx.setStorage({
                key: 'open_id',
                data: openid,
              });
              wx.getUserInfo({
                success: function (res) {
                  wx.request({
                    url: app.globalData.urlID1 + 'user.post.openid',
                    data: {
                      client: 'wx',
                      openid: openid
                    },
                    method: 'POST',
                    header: {
                      "Content-Type": "application/x-www-form-urlencoded"
                    },
                    success: function (res) {
                      console.log(res.data);
                      if (res.data.data == null) {
                        // console.log(app.globalData.userInfo.nickName)
                        wx.showModal({
                          content: '新用户需绑定手机号',
                          success: function (res) {
                            if (res.confirm) {
                              // 获取用户信息
                              wx.navigateTo({
                                url: '../m_set_phone/m_set_phone?openid=' + openid + '&nickname=' + that.data.userInfo.nickName,
                              })
                            } else {

                            }
                          }
                        })

                      } else {
                        wx.setStorage({
                          key: 'user_id',
                          data: res.data.data.id,
                        });
                        wx.setStorage({
                          key: 'user_info',
                          data: res.data.data.info,
                        });
                        wx.setStorage({
                          key: 'user_mobile',
                          data: res.data.data.mobile,
                        })

                        app.globalData.user_id = res.data.data.id;
                        console.log(app.globalData);
                        var employee={
                          user_id:app.globalData.user_id,
                          mobile:res.data.data.mobile
                        }
                        util.getData(app.globalData.urlID1+'user.get.employee',employee).then(function(res){
                          console.log(res.data);
                          if(res.data.code==3){
                            that.setData({
                              points: res.data.data.points
                            });
                            var awards={
                              user_id:app.globalData.user_id,
                              draw_id:1
                            };
                            util.getData(app.globalData.urlID+'lucky.get.luckydrawinfo',awards).then(function(res){
                              console.log(res.data);
                              that.setData({
                                awards:res.data.data
                              });
                              that.draw();
                            })
                          }
                        })
                      }
                    }
                  })
                },
                fail: function (res) {
                  wx.showModal({
                    title: '',
                    content: '部分功能需用户授权才能正常使用',
                    success: function (res) {
                      if (res.confirm) {
                        wx.openSetting({
                          success: (res) => {
                            console.log("授权结果")
                          }
                        })
                      } else if (res.cancel) {
                        console.log("用户点击取消")
                      }
                    }
                  })
                }
              })
            } else {
              console.log("获取用户openId失败");
            }
          }
        })
      }
    })
  },
  /**
   * 根据user_id查询个人信息
   */
  getUser: function (userId) {
    var that = this;
    var userInfo = {
      user_id: userId,
      mobile:''
    }
    wx.getStorage({
      key: 'user_mobile',
      success: function(res) {
        userInfo.mobile=res.data;
        util.getData(app.globalData.urlID1 + 'user.get.employee', userInfo).then(function (res) {
          console.log(res.data);
          if (res.data.code == 3) {
            app.globalData.awards_id=res.data.data.id;
            wx.setStorage({
              key: 'awards_id',
              data: res.data.data.id,
            })
            that.setData({
              points: res.data.data.points,
              awardsID:res.data.data.id
            });
            var awards = {
              user_id: app.globalData.user_id,
              draw_id: 1
            };
            util.getData(app.globalData.urlID + 'lucky.get.luckydrawinfo', awards).then(function (res) {
              console.log(res.data);
              that.setData({
                awards: res.data.data
              });
              that.draw();
            })
          }
        })
      },
    })
  },
  getLottery: function () {
    var that = this
    var awards = that.data.awards.length;
    var lucky={
      user_id:that.data.awardsID,
      draw_id:1
    };
    var awardIndex = parseInt(Math.random() * awards);//中奖物品的下标（测试用例）,此处下标应该是通过请求后台返回
    util.getData(app.globalData.urlID+'lucky.get.luckydraw',lucky).then(function(res){
      console.log(res.data.data.id);
      for(var i=0;i<awards;i++){
        if(that.data.awards[i].id==res.data.data.id){
          var awardIndex=i;
          var runDegs = 360 * awards - awardIndex * (360 / awards);//转到中奖物品所要的度数
          console.log(runDegs);

          var animationRun = wx.createAnimation({
            duration: 4000,
            timingFunction: 'ease'
          })
          that.animationRun = animationRun
          animationRun.rotate(runDegs).step()
          that.setData({
            animationData: animationRun.export(),
            btnDisabled: 'disabled',
            points: that.data.points - that.data.join
          });
          setTimeout(function () {
            wx.showModal({
              content: '获得' + that.data.awards[awardIndex].prize,
              showCancel: false,
              success: function (res) {
                if (res.confirm) {
                  //初始化转盘状态
                  var animationInit = wx.createAnimation({
                    duration: 10
                  })
                  that.animationInit = animationInit;
                  animationInit.rotate(0).step()
                  that.setData({
                    animationData: animationInit.export(),
                    btnDisabled: that.data.points == 0 ? 'disabled' : ''
                  })
                }
              }
            })
          }.bind(that), 4000);
          break;
        }
      }
    })
    
    console.log(awardIndex);

    
  },
  draw:function(){
    var that = this;
    var R=(that.data.width-100)/2;

    // 绘制转盘
    var awardsConfig = that.data.awards,
      len = awardsConfig.length,
      rotateDeg = 360 / len / 2 + 90,
      html = [],
      turnNum = 1 / len  // 文字旋转 turn 值
    var ctx = wx.createCanvasContext('welfare');
    for (var i = 0; i < len; i++) {
      // 保存当前状态
      ctx.save();
      // 开始一条新路径
      ctx.beginPath();
      // 位移到圆心，下面需要围绕圆心旋转
      ctx.translate(R, R);
      // 从(0, 0)坐标开始定义一条新的子路径
      ctx.moveTo(0, 0);
      // 旋转弧度,需将角度转换为弧度,使用 degrees * Math.PI/180 公式进行计算。
      ctx.rotate((360 / len * i - rotateDeg) * Math.PI / 180);
      // 绘制圆弧
      ctx.arc(0, 0, R, 0, 2 * Math.PI / len, false);

      // 颜色间隔
      if (i % 2 == 0) {
        ctx.setFillStyle('#fd8f16');
      } else {
        ctx.setFillStyle('#ffcd00');
      }

      // 填充扇形
      ctx.fill();
      // 绘制边框
      // ctx.setLineWidth(0.5);
      // ctx.setStrokeStyle('rgba(228,55,14,0)');
      // ctx.stroke();

      // 恢复前一个状态
      ctx.restore();


      // 奖项列表,turn为rotate的一种单位（圈）
      html.push({ turn: i * turnNum + 'turn', lineTurn: i * turnNum + turnNum / 2 + 'turn', award: awardsConfig[i].prize });
    }
    ctx.draw();
    that.setData({
      awardsList: html
    });
  },

  /**
   * clock
   */
  drawClock:function(){
    const ctx=wx.createCanvasContext('clock');
    let R = (this.data.width - 40)/2;

    // 画外框
    function drawOuter() {
      // 设置线条的粗细，单位px
      ctx.setLineWidth(1);
      ctx.setStrokeStyle('white');
      // 开始路径
      ctx.beginPath();
      // 运动一个圆的路径
      // arc(x,y,半径,起始位置，结束位置，false为顺时针运动)
      ctx.arc(0, 0, R, 0, 2 * Math.PI, false);
      ctx.setFillStyle('#ff6100');
      ctx.closePath();
      // 描出点的路径
      ctx.stroke();
      ctx.fill();
    };
    // 画内框
    function drawInner() {
      // 设置线条的粗细，单位px
      ctx.setLineWidth(8);
      ctx.setStrokeStyle('#942c05');
      // 开始路径
      ctx.beginPath();
      // 运动一个圆的路径
      // arc(x,y,半径,起始位置，结束位置，false为顺时针运动)
      ctx.arc(0, 0, R-24, 0, 2 * Math.PI, false);
      ctx.setFillStyle('#fef3c6');
      ctx.closePath();
      // 描出点的路径
      ctx.stroke();
      ctx.fill();
    };

    // 画数字对应的点
    function drawdots() {
      for (let i = 0; i < 32; i++) {
        var rad = 2 * Math.PI / 32 * i;
        var x = (R -10) * Math.cos(rad);
        var y = (R -10) * Math.sin(rad);
        ctx.beginPath();
        // 每5个点一个比较大
        if (i % 2 == 0) {
          ctx.setFillStyle('white');
          ctx.arc(x, y, 4, 0, 2 * Math.PI, false);
          ctx.setShadow(0, 0, 6, 'white');
        } else {
          ctx.arc(x, y, 2, 0, 2 * Math.PI, false);
          ctx.setFillStyle('black');
          ctx.setShadow(0, 0, 0, 'white');
        }
        ctx.fill();
      }
      ctx.closePath();
    }

    ctx.translate(R, R);
    drawOuter();
    drawInner();
    drawdots();
    ctx.draw();
  }

})
