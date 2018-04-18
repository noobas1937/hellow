<?php

namespace app\admin\controller\activity;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Activity extends Backend
{
    
    /**
     * TbActivity模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\\gyo2o\\dao\\ActivityDao');
        $this->view->assign('hot_yn',$this->model->getHotyn());

    }
    

    

}
