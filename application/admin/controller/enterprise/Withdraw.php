<?php

namespace app\admin\controller\enterprise;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 提现记录管理
 *
 * @icon fa fa-circle-o
 */
class Withdraw extends Backend
{
    
    /**
     * TbEmployeeWithdraw模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\\gyo2o\\dao\\EmployeeWithdrawDao');

    }

    public function index()
    {
        //设置过滤方法
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

            $result = $salaryModel->getWithdrawWaste($where, $sort, $order, $offset, $limit);

            return json($result);
        }
        return $this->view->fetch();
    }

    public function confirm($id){
        $withdrawDao = new \gyo2o\model\Withdraw();
        $result = $withdrawDao->confirm($id);
        if($result){
            $this->success();
        }else{
            $this->error();
        }
    }


}
