<template>
   
  <div class="site">
    <div class="se-1">请选择站点</div>
    <ul class="se-2">
        <li v-for='(item,index) of dataSite' :key="index"> 
            <span class="se-3">{{item.name}}</span>
            <el-radio class="radio" id="radios" v-model="radio" :label="item.Id+' '+item.name" size="large"><br></el-radio>
        </li>
    </ul>
   
   
    <div class="se-4" @click="msSite">确认</div>
  </div>
</template>

<script>
export default {
  data () {
      return {
        radio: '',
        dataSite:[]
      };
    },
    created(){
       this.getSitename()
    },
    methods:{
       
       msSite(){
         var radio=this.radio;
        
           this.$router.push({ name: 'Seorder', params: { radio: radio }})
       }, 
       getSitename(){
           var self=this
           this.$http.get('api/order/getsits')
           .then(response=>{
               self.dataSite=response.data.data;
               console.log(response.data)
            })
           .catch(error=>{console.log(error)})
       }
    }
}
</script>

<style>
.site{
    text-align: left;
    font-size:30px;

    overflow: hidden;
}
.se-1{
    width:100%;
    padding:0 30px;
    height:80px;
    line-height:80px;
    border-bottom:1px solid #e1e1e1;
    color:#000;
}
.se-2 li{
    width:100%;
    padding:0 30px;
    height:80px;
    line-height:80px;
    display: flex;
    flex-direction: row;
    border-bottom:1px solid #e1e1e1;
}
.se-3{
   display:block;
   width:600px;
}
#radios{
    width:40px;
    height:40px;
}
.se-4{
    height:100px;
    width:100%;
    background: #f39d0a;
    text-align: center;
    line-height: 100px;
    font-size:30px;
    color:#fff;
    position: fixed;
    left:0;
    bottom:0;

}
</style>


