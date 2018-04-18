<?php

namespace app\admin\controller\lucky;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 大转盘抽奖活动管理
 *
 * @icon fa fa-circle-o
 */
class Luckydraw extends Backend
{
    
    /**
     * TbLuckyDraw模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\\gyo2o\\dao\\LuckyDrawDao');

    }

    public function add()
    {
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            $params['left_with_people'] = $params['with_people'];
            $result = $this->model->insert($params);
            $this->result_return($result);
        }
        return $this->view->fetch();
    }

    

}
