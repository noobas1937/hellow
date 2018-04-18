<?php

namespace app\admin\controller\order;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 订餐管理
 *
 * @icon fa fa-circle-o
 */
class Send extends Backend
{

    /**
     * Order模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('gyo2o\\dao\\OrderDao');
        $this->view->assign("dishesTypeList", $this->model->getDishestypelist());
    }


    //订单列表
    public function index()
    {


        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $sid = \Think\Session::get('admin.site_id');
        if ($this->request->isAjax())
        {

            $date = !empty($this->request->get('date'))?$this->request->get('date'):date('Y-m-d',time());
            $order = new \gyo2o\model\Order();
            //按天统计订单
            $list = $order->orderlist($date,$sid);

            $result = array("total" => 3, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

}
