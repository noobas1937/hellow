<?php

namespace app\admin\controller\enterprise;

use app\common\controller\Backend;

use gyo2o\model\LuckyDrawRecord;
use think\Controller;
use think\Request;

/**
 * 抽奖纪录管理
 *
 * @icon fa fa-circle-o
 */
class Lucky extends Backend
{
    public function index($ids = null)
    {

        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $salaryModel = new \gyo2o\model\Withdraw();

            $result = $salaryModel->getWithdrawWaste($where, $sort, $order, $offset, $limit,$ids);

            return json($result);
        }
        $this->view->assign('ids',$ids);
        return $this->view->fetch();
    }

    public function edit($ids = '')
    {
        $enterprise = new LuckyDrawRecord();
        if($this->request->isPost()){
            $params = $this->request->post("row/a", [], 'strip_tags');
            $result = $enterprise->edit($ids,$params);
            $this->result_return($result);
        }
        $row = $enterprise->get_one($ids);
        if(!$row){
            $this->error('Parameter can not be empty');
        }

        $this->view->assign('row',$row);
        return $this->view->fetch();
    }
}
