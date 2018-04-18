<template>
   <div class="modal-container bg-1" v-show="modalshow" >
     <div class="modal-cover"></div>
     <div class="modal-content column">
         <span class="fs-3 cr-2 modal-content-headtit">请先完成员工身份认证</span>
         <span class="fs-1 cr-6 modal-content-contit">本程序仅供踢踢科技内部员工使用，非员工无法通过认证</span>
         <div class="modal-content-click">
             <span  class="modal-content-clclick bg-5 cr-1 lef" @click="goHello">随便逛逛</span>
             <span  class="modal-content-msclick bg-9 cr-1 rig" @click="goIdentity">立即认证</span>
         </div>
     </div>
   </div>
</template>



<script>
import tabbar from '../store/tabbar'

export default {
    data(){
        return{

        }
    },
    props:{
        modalshow:Boolean,
       // modalOnSure:Function,
       // modalCancel:Function
    }
    ,
    created(){

    },
    methods:{
      goHello(){
         this.$router.push({
             name:'HelloWorld'
         })
         tabbar.commit('changeHome');
       //  this.modalCancel();
      },
      goIdentity(){
         this.$router.push({
             name:'nidentity'
         })
        // this.modalfunc();
      },
     // modalOnSure(event){
      //    callback(event);
     // }, 
     // modalCancel(event){
     //     callback(event);
     // },  
    }
}
</script>




<style>
.modal-container{
    width:100%;
    height:100%;
    position: fixed;
    left:0;
    top:0;
    z-index: 9999;
}
.modal-cover{
    width:100%;
    height:100%;
    background: #000;
    opacity: 0.4;
    position: fixed;
    left:0;
    top:0;
    z-index: 9999;
}
.modal-content{
    width:606px;
    height:360px;
    background: #fff;
    border-radius: 14px;
    text-align: center;
    position: fixed;
    left:50%;
    top:50%;
    margin:-180px 0 0 -303px;
    z-index: 9999;
}
.modal-content-headtit{
    line-height: 100px;
    font-weight: bold;
}
.modal-content-contit{
     padding:10px 45px;
}
.modal-content-click{
   padding:30px;
}
.modal-content-clclick,.modal-content-msclick{
    display:block;
    width:200px;
    height:80px;
    text-align: center;
    line-height: 80px;
    border-radius: 14px;
}

</style>

