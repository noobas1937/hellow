<?php

namespace app\admin\controller\forum;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Topicimg extends Backend
{
    
    /**
     * TbTopicImg模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\\gyo2o\\dao\\TopicImgDao');

    }
    
    public function edit($ids=null)
    {
        $id = input('ids');
        $type = input('type');
        $topicImgModel = new \gyo2o\model\TopicImg();
        $img = $topicImgModel->getImgByTypeAndTpicid($type,$id);
        if ($this->request->isPost()){
            $param = $this->request->post('row/a');
            if($topicImgModel->modifyImg($param['img_id'],$type,$id)){
                $this->success();
            }else{
                $this->error('error');
            }
        }
        $this->view->assign('imgs',$img);
        return $this->view->fetch();
    }


}
