<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/28 0028
 * Time: 下午 3:00
 */

namespace app\admin\controller;


use app\common\controller\Backend;


class Account extends Backend
{

    /**
     * user_account 模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('gyo2o\\dao\\AccountDao');
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign("regList", $this->model->getRegList());

    }
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);

        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams('id');
            $account = new \gyo2o\model\Account();
            $result = $account->getAccounts($where,$sort,$order,$offset,$limit);
            return json($result);
        }
        return $this->view->fetch();
    }

}