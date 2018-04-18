<?php

namespace app\admin\controller\lucky;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 抽奖纪录管理
 *
 * @icon fa fa-circle-o
 */
class Luckydrawrecord extends Backend
{
    
    /**
     * TbLuckyDrawRecord模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\\gyo2o\\dao\\LuckyDrawRecordDao');

    }

    public function index($ids=null)
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);

        if ($this->request->isAjax())
        {
            $luckyDrawRecordModel = new \gyo2o\model\LuckyDrawRecord();
            list($where, $sort, $order, $offset, $limit) = $this->buildparams('employee_id');
            $result = $luckyDrawRecordModel->gerAwardRecord($where, $sort, $order, $offset, $limit,$ids);
            return json($result);
        }

        $this->assign('ids',$ids);
        return $this->view->fetch();
    }
    

}
