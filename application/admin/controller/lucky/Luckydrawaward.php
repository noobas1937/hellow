<?php

namespace app\admin\controller\lucky;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Luckydrawaward extends Backend
{
    
    /**
     * TbLuckyDrawAward模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\\gyo2o\\dao\\LuckyDrawAwardDao');
        $this->view->assign('type',$this->model->getAwardType());

    }

    public function index($ids=null)
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);

        if ($this->request->isAjax())
        {
            $luckyDrawAwardModel = new \gyo2o\model\LuckyDrawAward();
            $result = $luckyDrawAwardModel->getAwards($ids);
            return json($result);
        }

        $this->assign('ids',$ids);
        return $this->view->fetch();
    }

    public function add(){
        //设置过滤方法
        $this->request->filter(['strip_tags']);

        $luckyDrawId = input('ids');
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            $params['lucky_draw_id'] = $luckyDrawId;
            if ($params && $luckyDrawId)
            {
                foreach ($params as $k => &$v)
                {
                    $v = is_array($v) ? implode(',', $v) : $v;
                }
                $params['left_number'] = $params['number'];
                $luckyDrawAwardModel = new \gyo2o\model\LuckyDrawAward();
                if($luckyDrawAwardModel->addAward($params)!==false){
                    $this->success();
                }else{
                    $this->error('error');
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }
    

}
