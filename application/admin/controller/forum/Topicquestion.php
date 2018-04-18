<?php

namespace app\admin\controller\forum;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 提问信息管理
 *
 * @icon fa fa-circle-o
 */
class Topicquestion extends Backend
{
    
    /**
     * TbTopicQuestion模型对象
     */
    protected $model = null;
    protected $searchFields = ['title','question'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\\gyo2o\\dao\\QuestionDao');
        $this->view->assign('rec_yn',$this->model->getRecyn());
        $this->view->assign('type',$this->model->getType());

    }
    

    

}
