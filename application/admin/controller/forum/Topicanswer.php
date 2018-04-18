<?php

namespace app\admin\controller\forum;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 答案信息管理
 *
 * @icon fa fa-circle-o
 */
class Topicanswer extends Backend
{
    
    /**
     * TbTopicAnswer模型对象
     */
    protected $model = null;
    protected $searchFields = ['answer'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\\gyo2o\\dao\\AnswerDao');
        $this->view->assign('best_yn',$this->model->getBestyn());
        $this->view->assign('r_yn',$this->model->getRyn());

    }

    public function index($ids=null)
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);

        if ($this->request->isAjax())
        {
            $answerModel = new \gyo2o\model\Answer();
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $result = $answerModel->getAnswerByQuestion($ids,$where, $sort, $order, $offset, $limit);
            return json($result);
        }

        $this->assign('ids',$ids);
        return $this->view->fetch();
    }

    public function add(){
        $id = input('ids');
        if ($this->request->isPost()){
            parent::add();
        }
        $this->view->assign('topic_id',$id);
        return $this->view->fetch();
    }
    

}
