<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1 0001
 * Time: 下午 5:12
 */

namespace app\admin\controller;


use app\common\controller\Backend;

class Dictionary extends Backend
{

    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('gyo2o\\dao\\DataDictionaryDao');


    }


}