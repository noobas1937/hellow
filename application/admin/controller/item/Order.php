<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/8 0008
 * Time: 上午 10:56
 */

namespace app\admin\controller\item;


use app\common\controller\Backend;
use gyo2o\model\Tborder;

class Order extends Backend
{
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('gyo2o\\dao\\TborderDao');


    }

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);

        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams('id');
            $itemModel = new \gyo2o\model\Tborder();
            $result = $itemModel->getOrders($where, $sort, $order, $offset, $limit);
            return json($result);
        }

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
                    $tborderModel = new Tborder();
                    $result = $tborderModel->setOrderStatus($params);
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
        $this->view->assign('refundstatus',['5'=>'同意','6'=>'不同意']);
        return $this->view->fetch();
    }

    public function status(){
        return json(['-1'=>'取消','0'=>'待付款','1'=>'待确认','2'=>'待评价','3'=>'已完成','4'=>'退款中','5'=>'退款成功','6'=>'退款失败']);
    }
}