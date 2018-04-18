<?php

namespace app\admin\controller\item;

use app\common\controller\Backend;

use gyo2o\model\OrderStatus as Ostatus;

/**
 * 订单状态管理
 *
 * @icon fa fa-circle-o
 */
class Orderstatus extends Backend
{
    
    /**
     * TbOrderStatus模型对象
     */
    protected $model = null;
    private $subStatus = ['备货中','备货失败','骑手接单中','接单失败','配送中','配送成功','配送失败'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\\gyo2o\\dao\\OrderStatusDao');

    }

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $id = input('ids');
        if ($this->request->isAjax())
        {
            $orderStatus = new \gyo2o\model\OrderStatus();
            $result = $orderStatus->getOrderStatusList($id);
            return json($result);
        }

        $this->view->assign('ids',$id);
        return $this->view->fetch();
    }

    public function add(){
        $ids = input('ids');
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                foreach ($params as $k => &$v)
                {
                    $v = is_array($v) ? implode(',', $v) : $v;

                }
                try {
                    $orderStatusModel = new Ostatus();
                    $result = $orderStatusModel->setOrderStatus($params['orderid'],$this->subStatus[$params['status']]);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error();
                    }
                }catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign('orderid',$ids);
        $this->view->assign('orderstatus',$this->subStatus);
        return $this->view->fetch();
    }

    public function status(){
        return json(['-1'=>'取消','0'=>'待付款','1'=>'待确认','2'=>'待评价','3'=>'已完成','4'=>'退款中','5'=>'退款成功','6'=>'退款失败']);
    }
    

}
