<?php

namespace app\admin\controller\order;

use app\admin\model\Rider;
use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 骑手订单
 *
 * @icon fa fa-circle-o
 */
class Riderorder extends Backend
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
        $this->view->assign("orderStatusList", $this->model->getOrderstatuslist());
    }


    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $riderid = input('ids');
        $month = input('month')?input('month'):date('Y-m',time());
        if ($this->request->isAjax())
        {
            $ridermodel = new \gyo2o\model\Rider();
            $res = $ridermodel->riderorder($riderid,$month);
            $dtotal = count($res['list']);
            $result = array("total" => $res['total'], "rows" => $res['list'],"dtotal"=>$dtotal);

            return json($result);
        }

        $this->assign('ids',$riderid);
        $this->assign('month',$month);
        return $this->view->fetch();
    }
}
