<?php

namespace app\admin\controller\enterprise;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 奋斗金 工资管理
 *
 * @icon fa fa-circle-o
 */
class Singlesalarydetail extends Backend
{
    
    /**
     * TbCreditsIncreasementDetail模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\\gyo2o\\dao\\CredisIncreasementDetailDao');

    }
    

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $ids = input('ids');
        if ($this->request->isAjax())
        {
            $salaryModel = new \gyo2o\model\Salary();
            $record = $salaryModel->getSingleDetaliAll($ids);
            return json($record);
        }
        $this->view->assign('ids',$ids);
        return $this->view->fetch();

    }


}
