<?php

namespace app\admin\controller\item;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 评价管理
 *
 * @icon fa fa-circle-o
 */
class Itemeval extends Backend
{
    
    /**
     * TbEval模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\\gyo2o\\dao\\EvalDao');

    }

    public function index($ids=null)
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);

        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams('id');
            $evalModel = new \gyo2o\model\ItemEval();
            $result = $evalModel->getEvalsByItemId($where, $sort, $order, $offset, $limit,$ids);
            return json($result);
        }

        $this->assign('ids',$ids);
        return $this->view->fetch();
    }

    public function add(){
        $itemEval = new \gyo2o\model\ItemEval();
        $id = input('ids');
        $evalData = $itemEval->getEvalData($id);
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                foreach ($params as $k => &$v)
                {
                    $v = is_array($v) ? implode(',', $v) : $v;

                }
                try {
                    $result = $itemEval->replay($id,$params['content']);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($this->model->getError());
                    }
                }catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign('data',$evalData);
        return $this->view->fetch();
    }


    

}
