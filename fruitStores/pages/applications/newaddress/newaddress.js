var app=getApp();
var repath=app.globalData.rePath;
var tcity = require("../../../address.js");
Page({
  data: {
    flag:false,
    amc:{},
    value: [0, 0, 0],
    animationInfo: {},
    flag2:true,
    open:0,
    prolist:[],
    citlist:[],
    is_default:0
  },
  onLoad() {
    tcity.init(this);
    var cityData=this.data.cityData;
    var province=[];
    //console.log(cityData);
    for(var i=0;i<cityData.length;i++){
       province.push(cityData[i])
      }
      //console.log( province)
    
     var cities=province[0].sub;
     var areas=cities[0].sub;
    this.getProvince();
    this.setData({
       pro:province,
       cities:cities,
       areas:areas
      })
   var that=this;
   my.getStorage({
     key: 'upaddress',
     success: (res) => {
       console.log(res.data);
       that.setData({
         upa:res.data,
       })
     },
   });
  },
  selectArea(){
     this.setData({
       open:1
     })
  },
  setAddress(){
    this.data.flag=!this.data.flag;
    if(this.data.is_default==0){
      this.data.is_default=1;
    }
    else{
       this.data.is_default=0;
    }
    
    this.setData({
      flag:this.data.flag,
      is_default:this.data.is_default
    })
  },
  returnMaddress(){
    var upadd=this.data.upa;
    
    var name=this.data.name;
    var mobile=this.data.mobile;
    var is_default=this.data.is_default;
    var address=this.data.address;
    var proid=this.data.proid;
    var citid=this.data.citid;
    var areid=this.data.areid;
    console.log(areid);
    if(upadd){
       var that=this;
       var address_id=upadd.id;
     my.httpRequest({
     url:repath+"?action=user.address.save",
     method: 'POST',
     data: {
      user_id:25,
      name:name,
      mobile:mobile,
      province:proid,
      area:areid,
      city:citid,
      address:address,
      is_default:is_default,
      address_id:address_id
    },
     dataType: 'json',
     success: function(res) {
        my.removeStorage({
          key: 'upaddress', // 缓存数据的 key
          success: (res) => {
           my.redirectTo({
             url:'../maddress/maddress'
           })
          },
        });
       
   }, 
  });
    }
    else{
     var that=this;
    my.httpRequest({
     url:repath+"?action=user.address.add",
     method: 'POST',
     data: {
      user_id:25,
      name:name,
      mobile:mobile,
      province:proid,
      area:areid,
      city:citid,
      address:address,
      is_default:is_default
    },
     dataType: 'json',
     success: function(res) {
        my.redirectTo({
          url:'../maddress/maddress'
       })
   }, 
  });
    }
   
   
   
  },
 
 cityChange(e){
   
    var value = e.detail.value;
    //console.log(value);
    //console.log(value[0])
    var p=value[0];
    var c=value[1];
    var a=value[2];
   // console.log(this.data.pro[p])
    if(this.data.value[0]!=p){
      var pr=this.data.pro[p];
      var ci=pr.sub;
      
      var ar=ci[0].sub;
      //console.log(ar);
      this.setData({
       value:[p,0,0],
       cities:ci,
       areas:ar
    })
  }
  else if(this.data.value[1]!=c){
      var pr=this.data.pro[p];
      var ci=pr.sub[c];
     // console.log(ci)
      var ar=ci.sub;
      this.setData({
       value:[p,c,0],
       areas:ar
    })
  }
  else if(this.data.value[1]!=a){
      //var pr=this.data.pro[p].name;
     // console.log(this.data.pro[p])
     //// var ci=pr.sub[c].name;
     // var ar=ci.sub[a].name;
      this.setData({
       value:[p,c,a],
       maddress:pr+ci+ar
      })
    
  }
     //console.log(this.data.value);
     //console.log(this.data.pro[this.data.value[0]].name)
     //console.log(this.data.pro[this.data.value[0]].sub[this.data.value[1]].name)
     //console.log(this.data.pro[this.data.value[0]].sub[this.data.value[1]].sub[this.data.value[2]].name) 
},
    cancelAddress(){

    },
    msAddress(){

    },
    cancelBtn(){
      this.setData({
         open:0,
      })
    },
    msureBtn(){
       var value=this.data.value;
       var tarea=this.data.prolist[this.data.value[0]].name+this.data.citlist[this.data.value[1]].name+this.data.arelist[this.data.value[2]].name;
       this.setData({
         open:0,
         proid:this.data.prolist[value[0]].id,
         citid:this.data.citlist[value[1]].id,
         areid:this.data.arelist[value[2]].id,
         tarea:tarea
        })
        console.log(this.data.tarea)
      },
    getName(e){
        var name=e.detail.value;
        console.log(name);
         this.setData({
           name:name
         })
      },
    getMobile(e){
      var mobile=e.detail.value;
      var ms=/^1[3|4|5|8][0-9]\d{4,8}$/;
      if(!ms.test(mobile)){
           my.alert({
             title: '输入有误', // alert 框的标题
             success: (res) => {
               
             },
           });
          }
     else{
         this.setData({
           mobile:mobile
         })
       }
      },
    getAddress(e){
       var address=e.detail.value;
       console.log(address)
       if(address){
         this.setData({
           address:address
         })
        }
       else{
         my.alert({
           title: '请输入地址', // alert 框的标题
           success: (res) => {
             
           },
         });
       }
      },
  getPCA(e){
       this.setData({
         value:e.detail.value,
        })
        this.getProvince();  
  },
  getProvince(){
    var val=this.data.value;
    console.log(val);
    var that=this;
     my.httpRequest({
     url:repath+"?action=data.province.list",
     method: 'POST',
     data: {
      user_id:25,
      page:1,
      page_size:5
    },
     dataType: 'json',
     success: function(res) {
       console.log(res.data)
       that.setData({
         prolist:res.data.data,
        })
        var id1=res.data.data[val[0]].id
        that.getCities(id1);
       },
      });
  },
  getCities(ids){
     var val=this.data.value;
     var prolist=this.data.prolist;
     console.log(prolist);
     var that=this;
     my.httpRequest({
      url:repath+"?action=data.province.pid",
      method: 'POST',
      data: {
        pid:ids,
        user_id:25,
        page:1,
        page_size:5
             },
        dataType: 'json',
        success: function(res) {
          console.log(res.data);
         
            that.setData({
             citlist:res.data.data,
            })
             var id2=res.data.data[val[1]].id
            that.getAreas(id2);
          },
      });
  },
  getAreas(ids){
     var that=this;
     var val=this.data.value;
     var arelist=this.data.arelist;
     my.httpRequest({
      url:repath+"?action=data.province.pid",
      method: 'POST',
      data: {
        pid:ids,
        user_id:25,
        page:1,
        page_size:5
             },
        dataType: 'json',
        success: function(res) {
          console.log(res.data);
          var id=res.data.data[0].id
            that.setData({
             arelist:res.data.data,
            })
          },
      });
  } 
});
