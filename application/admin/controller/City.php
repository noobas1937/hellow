<?php

namespace app\admin\controller;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 城市管理
 *
 * @icon fa fa-circle-o
 */
class City extends Backend
{
    
    /**
     * City模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('gyo2o\dao\CityDao');

    }

    

}
