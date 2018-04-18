<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/29 0029
 * Time: 下午 4:29
 */

namespace app\admin\controller;


use app\common\controller\Backend;

class Userinfo extends Backend
{
    /**
     * userinfo
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('gyo2o\\dao\\UserInfoDao');
        $this->assign('sexList',$this->model->sexList());


    }

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $ids = input('ids');
        if ($this->request->isAjax())
        {

            $account = new \gyo2o\model\Account();
            $result = $account->getUserInfo($ids);
            return json($result);
        }
        $this->assign('ids',$ids);
        return $this->view->fetch();
    }

}