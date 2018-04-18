<?php

namespace app\admin\controller\activity;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 首页头条信息管理
 *
 * @icon fa fa-circle-o
 */
class Headline extends Backend
{
    
    /**
     * TbHeadline模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\\gyo2o\\dao\\HeadlineDao');

    }

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);

        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams('title');

            $headLineModel = new \gyo2o\model\Headline();
            $result = $headLineModel->getHeadlines($where,$sort,$order,$offset,$limit);

            return json($result);
        }
        return $this->view->fetch();
    }


}
