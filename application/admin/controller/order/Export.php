<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/22 0022
 * Time: 上午 11:28
 */

namespace app\admin\controller\order;

use Think\Session;
use app\common\controller\Backend;

class Export extends Backend
{
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $sid = Session::get('admin.site_id');
        if ($this->request->isAjax())
        {

            $startdate = !empty($this->request->get('startdate'))?$this->request->get('startdate'):date('Y-m-d',time());
            $enddate = !empty($this->request->get('enddate'))?$this->request->get('enddate'):date('Y-m-d',time());;
            if(empty($startdate)||empty($enddate)){
               return json(['total'=>0,'rows'=>[]]);
            }
            $dates = [];
            $i = 0;
            while(strtotime("+$i day",strtotime($startdate))<=strtotime($enddate)){
                $dates[] = date('Y-m-d',strtotime("+$i day",strtotime($startdate)));
                $i++;
            }

            $order = new \gyo2o\model\Order();
            $data = [];
            //按天统计订单
            foreach ($dates as $date){
                $list = $order->orderlist($date,$sid);
                $data = array_merge($data,$list);
            }


            $result = array("total" => 3, "rows" => $data);
            return json($result);
        }
        return $this->view->fetch();
    }

}