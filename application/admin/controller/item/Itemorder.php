<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6 0006
 * Time: 下午 1:57
 */

namespace app\admin\controller\item;


use app\common\controller\Backend;

class Itemorder extends Backend
{

    public function index($ids=null)
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);

        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams('id,sn1,sn2,mobile');
            $tborderModel = new \gyo2o\model\Tborder();
            $result = $tborderModel->getOrdersByItemid($where, $sort, $order, $offset, $limit,$ids);
            return json($result);
        }

        $this->assign('ids',$ids);
        return $this->view->fetch();
    }
}