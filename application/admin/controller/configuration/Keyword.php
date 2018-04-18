<?php

namespace app\admin\controller\configuration;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 关键词管理
 *
 * @icon fa fa-circle-o
 */
class Keyword extends Backend
{
    
    /**
     * KeyWord模型对象
     */

    protected $searchFields = ['word'];
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\\gyo2o\\dao\\KeyWordDao');

    }
    
    
    

}
