<?php

namespace app\admin\controller\lucky;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Luckyticket extends Backend
{
    
    /**
     * TbLuckyTicket模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\\gyo2o\\dao\\LuckyTicketDao');
        $this->view->assign('type',$this->model->getTicketType());

    }


}
