<template>
  <transition name="slide-fade">
     <div class="picker-con" v-if="showPicker">
          <div class="picker-cover-1 bg-2"></div>
          <div class="picker-cover-2 bg-1 column">
               <div class="picker-btn">
                   <span class="picker-btn-cancel lef" @click="cancel">取消</span>
                   <span class="picker-btn-comfirm rig" @click="confirm">确定</span>
               </div>
               <div class="picker-select">
                   <span class="picker-select-child bg-5 fs-m cr-1" v-for="(item,index) in pickerSelects" :key="index" @click="preInfo(index)">{{item.name}}</span>
               </div>

               <div class="picker-content column">
                   <ul class="picker-content-ul column">
                       <li v-for="(item,index) in pickerInfo" :key="index" @click="showInfo(index)" :class="disableBtn?'showsA':'showsB'">{{item.name}}</li>
                      
                   </ul>
               </div>
          </div>
     </div>
  </transition>
 
</template>


<script>


export default {
    data(){
        return{
            
        }
    },
    props:{
        showPicker:Boolean,
        pickerInfo:Array,
        pickerSelects:Array,
        disableBtn:Boolean
    },
    computed: {
     
    },
    methods:{
        showInfo:function(index){
            
            this.index=index;
            this.$emit('addInfo',this.index);  
        },
        preInfo:function(){
            this.$emit('subInfo',this); 
        },
        cancel:function(){
           
            this.$emit('cancelBtn',this); 
        },
        confirm:function(){
           
            this.$emit('confirmBtn',this); 
        }
    }
}
</script>


<style>
.slide-fade-enter-active {
  transition: all .3s ease;
}
.slide-fade-leave-active {
  transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active for below version 2.1.8 */ {
  transform: translateZ(10px);
  opacity: 0;
}
 .picker-con{
     width:100%;
     height:100%;
     position:fixed;
     left:0;
     top:0;
     z-index:99999;
 }
 .picker-cover-1{
     width:100%;
     height:100%;
     opacity:0.4;
     position:fixed;
     left:0;
     top:0;
 }
 .picker-cover-2{
     width:100%;
     height:480px;
     
     position:fixed;
     left:0;
     bottom:0;
     
 }
 .picker-btn{
    height:15%;
    padding:0 10px;
 }
 .picker-content{
     height:300px;
     border-top:1px solid #bdbddb;
     height:70%;
    
 }
 .picker-select{
    height:15%;
 }
 .picker-content-child{
     height:10%;
 }
 .picker-content-ul{
     width:100%;
     
 }
 .picker-content-ul>li{
     padding:0 20px;
     height:60px;
    
 }
 .picker-select-child{
     padding:6px 10px;
     border:1px solid #bdbdbd;
     border-radius: 6px;
     margin-left:10px;
 }
 .picker-btn-cancel{
     width:50%;
 }
 .picker-btn-confirm{
     width:50%;
 }
 .showsA{
   
 }
 .showsB{
   pointer-events:none;
 }
</style>

