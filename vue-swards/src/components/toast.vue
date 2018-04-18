<template>
 <!-- <transition name="toast-fade">
    <div class="toast"
      :class="objClass" 
      v-show="isActive"
      @mouseenter="onMouseenter"
      @mouseleave="onMouseleave"
      >
      <button class="toast-close-button" @click="hide">×</button>
      <div class="toast-container">
        <div class="toast-title">{{title}}</div>
        <div class="toast-content">{{content}}</div>
      </div>
    </div>
  </transition>-->

<transition name="toast-fade">
  <div class="cover" v-show="isActive">
      <div class="cover-1"></div>
      <div class="cover-2t cr-2 fs-2 column">
        <div class="toast-title">{{title}}</div>
        <div class="toast-content">{{content}}</div>
      </div>
  </div>
</transition>
</template>
 
<script>
 export default {
  data: () => ({
    list: [],
    title: null,
    content: null,
    type: null,
    isActive: false,
    timer: null,
    onShow: () => {},
    onHide: () => {},
    onFail:() => {},
    onSuccess:() => {},
  }),
  computed: {
  },
  methods: {
    // 显示
    show (params) {
      let {content, title, onShow, onHide, type,onFail,onSuccess} = params
      this.type = type
      this.content = content
      this.title = title
      this.onShow = onShow
      this.onHide = onHide
      this.isActive = true
      this.setTimer()
      this.onFail=onFail
      this.onSuccess=onSuccess
    },

    // 隐藏
    hide () {
      this.isActive = false
    },
   // onSuccess(event){
   //    callback(event)
   // },
    //onFail(event){
    ///   callback(event)
   // },
    // 计时器
    setTimer () {
      clearTimeout(this.timer)
      this.timer = setTimeout(() => {
        this.isActive = false
      }, 2000)
    },
  
  },
  watch: {
    isActive (val) {
      if (val && typeof this.onShow === 'function') {
        this.onShow()
      } else if (!val && typeof this.onHide === 'function') {
        this.onHide()
      }
    }
  }
}
</script>

<style>

.fade-enter-active, .fade-leave-active {
  transition: opacity 1s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}


.cover{
    width:100%;
    height:100%;
    position: fixed;
    left:0;
    top:0;
    z-index: 9999;

}
.cover-1{
    width:100%;
    height:100%;
    background: #000;
    opacity: 0.4;
    position: fixed;
    left:0;
    top:0;
     z-index: 9999;
}
.cover-2t{
    width:606px;
    height:300px;
    background: #fff;
    border-radius: 14px;
   
    text-align: center;
    position: fixed;
    left:50%;
    top:50%;
    margin:-150px 0 0 -303px;
    z-index: 9999;
    box-shadow: 0 0 8px #ff9900;
}
.toast-title{
    height:100px;
    line-height: 100px;
    font-weight: bold;
}
.toast-content{
   height:150px;
   margin-top:30px;
   line-height: 50px;
   display: block;
   
}
</style>