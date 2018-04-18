<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/8 0008
 * Time: 下午 4:24
 */

namespace app\admin\controller\item;


use app\common\controller\Backend;

class package extends Backend
{
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('gyo2o\\dao\\TbpackageDao');
        $this->searchFields = ['title'];
        $this->view->assign('rec_yn',$this->model->getRcyn());
    }

}