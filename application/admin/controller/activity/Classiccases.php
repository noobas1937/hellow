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
class Classiccases extends Backend
{
    
    /**
     * TbClassicCases模型对象
     */
    protected $model = null;
    protected $searchFields = ['title,id'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\\gyo2o\\dao\\ClassicCasesDao');

    }
    


}
