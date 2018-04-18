const chalk = require('chalk');
var i=24;

function add(){
    var a=new Date();
    var hh=a.getHours();
    var mm=a.getMinutes();
    var ss=a.getSeconds();
    var s=parseInt(Math.random()*100);
    console.log(s)
    if(s==i){
        var a=chalk.green('恭喜你中奖了');
    }
    else{
        var a=chalk.red('很遗憾你没有中奖');
    }
    if(hh<10){
       hh='0'+hh
    }
    if(mm<10){
       mm='0'+mm
    }
    if(ss<10){
       ss='0'+ss
    }
    var time=hh+':'+mm+':'+ss;
    if(i){
      console.log(time+'-'+a);
    }
    else{
       console.log('已经到了世界尽头！')
    }
}
setInterval(add,1000);
