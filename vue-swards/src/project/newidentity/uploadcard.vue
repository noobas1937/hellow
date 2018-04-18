<template>
   <div class="upl-container bg-8 column" :style="'height:'+newheight+'px;'">
       <span class="upl-1 fs-1 cr-6">3.请上传个人身份信息</span>
       <span class="upl-2 fs-1 cr-6">拍摄您的二代身份证原件，请确保图片清晰，四角完整</span>
       <div  class="upl-3">
           <div class="upl-3-1 lef column" :style="bg.background1">
               <img src="../../assets/img/ncir.png" alt="" class="upl-3-img" v-show="flag1">
               <span class="cr-2 fs-mm upl-3-txt" v-show="flag1">上传身份证人像页</span>
               <input type="file" accept="image/*" multiple class="upload-img" id="uploadimgs1" v-on:change="getOppositePhoto('#uploadimgs1',1)">
           </div>
           <div class="upl-3-2 rig column" :style="bg.background2">
               <img src="../../assets/img/ncir.png" alt="" class="upl-3-img" v-show="flag2">
               <span class="cr-2 fs-mm upl-3-txt" v-show="flag2">上传身份证人像页</span>
               <input type="file" accept="image/*" multiple class="upload-img" id="uploadimgs2" v-on:change="getOppositePhoto('#uploadimgs2',2)">
           </div>
       </div>
       <span  class="upl-4 fs-1 cr-6">手持身份证照片拍照上传，请确保五官清晰及证号可见</span>
       <div  class="upl-5">
           <div class="upl-5-1 column" :style="bg.background3">
                <img src="../../assets/img/ncir.png" alt="" class="upl-3-img1" v-show="flag3">
               <span class="cr-2 fs-mm upl-3-txt" v-show="flag3">上传身份证人像页</span>
                <input type="file" accept="image/*" multiple class="upload-img" id="uploadimgs3" v-on:change="getOppositePhoto('#uploadimgs3',3)">
           </div>
           
       </div>
       <wv-group >
        <wv-input label="身份证" :labelWidth="80" placeholder="请输入身份证号码" v-model="idcard" :required="true" :validate-mode="{onFocus: false, onBlur: true, onChange: false}">
        </wv-input>
       </wv-group>

       <div class="upl-6">
           <div class="upl-6-1 bg-9 cr-1 fs-3" @click="msSumbit">提交认证</div>
       </div>
   </div>
</template>




<script>
export default {
    data(){
        return{
            newheight:document.documentElement.clientHeight,
            flag1:true,
            flag2:true,
            flag3:true,
            img1:'',
            img2:'',
            img3:'',
            idcard:'',
            imgs: [],  
            imgData: {  
                accept: 'image/gif, image/jpeg, image/png, image/jpg',  
            },  
            bg:{
                background1:"background-image:"+"url(" + require("../../assets/img/nid1.jpg") + ");",
                background2:"background-image:"+"url(" + require("../../assets/img/nid2.jpg") + ");",
                background3:"background-image:"+"url(" + require("../../assets/img/nid3.jpg") + ");"
            }
        }
    },
    methods:{
        getOppositePhoto(a,b){
            console.log(a);
            var b=b;
            console.log(b);
            var c=document.querySelector(a);
            console.log(c.files[0]);
            
           

            let self=this;
            let reader =new FileReader();  
            let img1=c.files[0];  
            let type=img1.type;//文件的类型，判断是否是图片  
            let size=img1.size;//文件的大小，判断图片的大小  
            if(this.imgData.accept.indexOf(type) == -1){  
                alert('请选择我们支持的图片格式！');  
                return false;  
            }  
            if(size>3145728){  
                alert('请选择3M以内的图片！');  
                return false;  
            }  
            var uri = '';
            let form = new FormData();   
            console.log(img1);
            
            form.append('file',img1,img1.name); 
            this.$http.post('https://api.nacy.cc/upload/ajax/upload',form,{  
                headers:{'Content-Type':'multipart/form-data'}  
            }).then(response => {  
                console.log(response.data)  
                uri = response.data.data.url;
                var id=response.data.data.id;
                console.log(uri);
                if(b==1){
                    self.bg.background1= "background-image:"+"url(" + response.data.data.url + ");"; 
                    self.flag1=false;
                    self.img1=id;
                }else if(b==2){
                    self.bg.background2= "background-image:"+"url(" + response.data.data.url + ");"; 
                    self.flag2=false;
                    self.img2=id;
                }else if(b==3){
                    self.bg.background3= "background-image:"+"url(" + response.data.data.url + ");"; 
                    self.flag3=false;
                    self.img3=id;
                }
                
               // reader.readAsDataURL(img1);  
               // var that=this;  
               // reader.onloadend=function(){  
                //    that.imgs.push(uri);  
               // }  

            }).catch(error => {  
                console.log(error);
            })      
        },
        msSumbit(){
             let b=localStorage.getItem('rinfo');
             let c=JSON.parse(b);
             c.id_card_positive_imgid=this.img1;
             c.id_card_opposite_imgid=this.img2;
             c.id_card_hold_imgid=this.img3;
             c.idcard=this.idcard;
             console.log(c);
             
             
           
            var e={
                name:'11111',
                tb_user_id:112,
           
            }
            var self=this;
            this.$http.post('?action=user.post.employee.apply',c)
            .then(res=>{
              console.log(res);
              if(res.data.code==3){
                   localStorage.removeItem('rinfo');
                   localStorage.setItem('eid',-1);
                   this.$router.push({
                       name:'home'
                   })
              }
              else{
                  this.$toast({
                      content:res.data.msg,
                  })
              }
            })
            .catch(err=>{console.log(err)})
            }
    }
}
</script>



<style>
 .upl-container{
     width:100%;
    
    
 }
 .weui-cell{
    height:80px !important;
    font-size:14px !important;
    padding:0 30px !important;
}
 .upl-1{
    padding:10px 30px;
 }
 .upl-2{
    padding:0px 30px;
 }
 .upl-3{
    padding:0 3%;
    height:210px;
    margin:20px 0;
 }
 .upl-4{
   padding:15px 30px;
 }
 .upl-5{
     padding:0 30px;
    
 }
 .upl-5-1{
     height:438px;
     background-repeat: no-repeat;
     background-position: center;
     background-size: cover;
  
     position: relative;
 }
 .upl-3-1{
     width:47%;
     height:100%;
     background-repeat: no-repeat;
     background-position: center;
     background-size: cover;
    
     position: relative;
 }
 .upload-img{
     width:100%;
     height:100%;
     position: absolute;
     left: 0;
     top:0;
     opacity: 0;
 }
 .upl-3-2{
     width:47%;
     height:100%;
     background-repeat: no-repeat;
     background-position: center;
     background-size: cover;
    
     position: relative;
 }
 .upl-3-img{
     width:80px;
     height:80px;
    margin-top:14%;
    position: relative;
    left:50%;
    transform: translateX(-50%);
    -webkit-transform: translateX(-50%);
 }
 .upl-3-img1{
    width:120px;
    height:120px;
    margin-top:18%;
    position: relative;
    left:50%;
    transform: translateX(-50%);
    -webkit-transform: translateX(-50%);
 }
 .upl-3-txt{
     margin-top:10px;
     text-align: center;
 }
 
 .upl-6{
    padding:0 30px;
    margin-top:40px;
 }
 .upl-6-1{
    height:90px; 
    text-align: center;
    line-height: 90px;
    border-radius: 12px;
 }
</style>


