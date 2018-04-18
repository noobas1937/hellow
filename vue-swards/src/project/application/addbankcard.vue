<template>
   <div class="abc-container bg-8 column" :style="'height:'+newheight+'px;'">
        <span class="abc-1 cr-6 fs-1">1.拍摄持卡人本人的银行卡，请确保图片清晰四角完整</span>
        <span class="abc-2 cr-11 fs-1">为了资金可以更快到账，请尽量使用招商银行储蓄卡</span>
        <div class="abc-3 column" :style="bg.background1">
              <img class="abc-3-1" src="../../assets/img/ncir.png" alt="" v-show="flag1">
              <span  class="abc-3-2 fs-m cr-2" v-show="flag1">上传银行卡正面照片</span>
               <input type="file" accept="image/*" multiple class="upload-img" id="uploadimgs1" v-on:change="getOppositePhoto('#uploadimgs1',1)">
        </div>
        <span class="abc-4 cr-6 fs-1">2.请填写已上传的银行卡信息</span>

        <wv-group>
            <wv-input label="持卡人" :labelWidth="80" placeholder="请输入真实姓名" v-model="uname" :required="true" :validate-mode="{onFocus: false, onBlur: true, onChange: false}"></wv-input>
            <!-- <wv-input label="卡类型" :labelWidth="80" placeholder="招商银行储蓄卡" v-model="ubankcard_kind" :readonly="true"></wv-input> -->
            <wv-input label="卡类型"  :labelWidth="80" :value="dayAndTime"  @click.native="dayPickerShow=true" />
            <wv-input label="卡号" :labelWidth="80" placeholder="请输入卡号" type="number"  v-model="ubankcard_id" :required="true" :validate-mode="{onFocus: false, onBlur: true, onChange: false}"></wv-input>
            <wv-input label="开户行" :labelWidth="80" placeholder="请输入开户行名称" v-model="ubank_name" :required="true" :validate-mode="{onFocus: false, onBlur: true, onChange: false}"></wv-input>
        </wv-group>

          <wv-picker :visible.sync="dayPickerShow" :columns="dayColumns"  @change="onChange"/>

        <span class="abc-5 cr-11 fs-1">该银行卡将用于工资发放及奖金领取，请填写真实有效的卡号，并仔细核对。</span>
        <div class="abc-6" >
            <div  class="abc-6-1 cr-1 bg-9 fs-3" @click="submitBankInfo">提交认证</div>
        </div>
   </div>

</template>


<script>
import { Dialog } from "we-vue";
import { Toast } from "we-vue";
import { Picker } from "we-vue";

export default {
    
  data() {
    return {
      newheight: document.documentElement.clientHeight,
      uname: "",
      ubankcard_kind: "",
      ubankcard_id: "",
      ubank_name: "",
      flag1: true,
      imgs: [],
      imgId: "" ,
      imgData: {
        accept: "image/gif, image/jpeg, image/png, image/jpg"
      },
      bg: {
        background1:
          "background-image:" +
          "url(" +
          require("../../assets/img/bcard.png") +
          ");"
      },
      pnum1:0,
      listinfo: [],
      selectsf1: false,
      dayPickerShow: false,
      dayAndTime: "招商银行",
      dayColumns: [
        {
          values: [
             
          ],
          defaultIndex: 0
        }
      ]
    };
  },
  created() {
    this.initialize();
  },
  methods: {
    //上传图片
    getOppositePhoto(a, b) {
      console.log(a);
      var b = b;
      console.log(b);
      var c = document.querySelector(a);
      console.log(c.files[0]);

      let self = this;
      let reader = new FileReader();
      let img1 = c.files[0];
      let type = img1.type; //文件的类型，判断是否是图片
      let size = img1.size; //文件的大小，判断图片的大小
      if (this.imgData.accept.indexOf(type) == -1) {
        alert("请选择我们支持的图片格式！");
        return false;
      }
      if (size > 3145728) {
        alert("请选择3M以内的图片！");
        return false;
      }
      var uri = "";
      let form = new FormData();
      console.log(img1);
      form.append("file", img1, img1.name);
      this.$http
        .post("?action=upload.post.upload", form, {
          headers: { "Content-Type": "multipart/form-data" }
        })
        .then(response => {
          console.log(response.data.id);
          uri = response.data.url;
          self.imgId = response.data.data.id;
          console.log(response.data.data.id);
          if (b == 1) {
            self.bg.background1 =
              "background-image:" + "url(" + response.data.data.url + ");";
            self.flag1 = false;
          }

          // reader.readAsDataURL(img1);
          // var that=this;
          // reader.onloadend=function(){
          //    that.imgs.push(uri);
          // }
        })
        .catch(error => {
          alert("上传图片出错！");
        });

    },

    submitBankInfo() {
       var eid=localStorage.getItem('eid');
       var str = this.ubankcard_id.match(/^([1-9]{1})(\d{14}|\d{18})$/);
       var t1 = this.imgId;
       var t2 = this.uname;
       var t3 = this.dayAndTime;
       var t4 = this.ubank_name;
       console.log(t1,t2,t3,t4)
       console.log(str)
       if(str!=null &&t1&&t2&&t4){
          
       this.$http.post("?action=user.post.employee.bank",{img_id:this.imgId,employee_id:eid,username:this.uname,bank_name:this.dayAndTime,bank_card:this.ubankcard_id,branch_name:this.ubank_name}).then(res => {
            
    //   var self = this;
    //   if (this.uname) {
    //     Dialog({
    //       message: "绑定银行卡成功",
    //       skin: "ios",
    //       showCancelButton: true,
    //       showConfirmButton: true,
    //       cancelButtonText: "随便逛逛",
    //       confirmButtonText: "查看",
    //       handleAction: res => {
    //         console.log(res);
    //         if (res == "confirm") {
    //         } else {
    //           Dialog({
    //             visible: false
    //           });
    //         }
    //       }
    //     });
    //   } else {
    //     Toast({
    //       duration: 1000,
    //       message: "绑定失败",
    //       icon: "warn"
    //     });
    //   }
    Toast.success('操作成功');
      }) 
      .catch(error => {
          console.log(error);
        });
        
       }else{
           Toast.fail('操作失败');
       }
    },
    confirmPerson() {},
    initialize() {
      this.$http.post("?action=api.post.bank.list").then(res => {
          var a=res.data.data;
          this.listinfo = res.data.data;
          console.log(a)
          this.dayColumns[0].values = ["请选择"];
          for (let i = 0; i < a.length; i++) {
            this.dayColumns[0].values.push(a[i]);
          }
          console.log(this.dayColumns[0].values);
        })
        .catch(error => {
          console.log(error);
        });
    },
    //下拉
    onChange(picker, value) {
      this.$nextTick(() => {
        console.log(picker.getValues()[0]);
        console.log(picker.getIndexes());
        this.dayAndTime=picker.getValues().join('');
        this.pnum1 = picker.getIndexes()[0];
      });
    }
  }
};
</script>



<style>
.abc-container {
  width: 100%;
 
}
.abc-1 {
  padding: 30px;
}
.abc-2 {
  padding: 0 30px 20px 30px;
}
.abc-3 {
  position: relative;
  width: 486px;
  height: 304px;
  margin: 0 auto;
  background: url("../../assets/img/bcard.png")no-repeat center;
  background-size: cover;
 
}
.abc-3-1 {
  width: 110px;
  height: 110px;
  margin-top: 15%;
  position: relative;
  left: 50%;
  transform: translateX(-50%);
}
.abc-3-2 {
  text-align: center;
  margin-top: 3%;
  font-weight: bold;
}
.abc-4 {
  padding: 20px 30px 0 30px;
}
.abc-5 {
  padding: 20px 30px;
}
.abc-6 {
  padding: 0 30px;
}
.abc-6-1 {
  height: 90px;
  line-height: 90px;
  text-align: center;
  border-radius: 14px;
}
.weui-cell {
  height: 70px !important;
  font-size: 14px !important;
}
.weui-label {
  padding: 0 0 0 10px !important;
}
.upload-img {
  width: 100%;
  height: 100%;
  position: absolute;
  left: 0;
  top: 0;
  opacity: 0;
}
.card-1 .weui-cell__ft {
  flex: 3;
  text-align: left !important;
}
.card-1 .weui-cell__ft:after {
  display: none !important;
}
</style>


