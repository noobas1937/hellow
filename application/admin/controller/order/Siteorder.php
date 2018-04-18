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
class Siteorder extends Backend
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


    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $params = input('ids');
        $params = explode('-',$params,2);
        $siteid = $params[0];
        $date = $params[1];
        $dishesType = $this->request->has('dishes_type')?$this->request->get('dishes_type'):null;
        if ($this->request->isAjax())
        {

            $order = new \gyo2o\model\Order();
            $result = $order->siteOrder($date,$siteid,$dishesType);

            return json($result);
        }
        $this->assign('date',$date);
        $this->assign('siteid',$siteid);
        return $this->view->fetch();
    }
}
