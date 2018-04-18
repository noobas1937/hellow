<?php
namespace app\callback\controller;

use gyo2o\model\Tborder;
use think\Controller;

class Back extends Controller
{
   public function receive(){
       $param = input('param.');
       $tb_order = new Tborder();
       $result = $tb_order->receive($param);
       return json($result);
   }


}
