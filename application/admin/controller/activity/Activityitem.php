<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/8 0008
 * Time: 下午 5:11
 */

namespace app\admin\controller\activity;


use app\common\controller\Backend;

class Activityitem extends Backend
{
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('gyo2o\\dao\\ActivityItemDao');
//        $this->searchFields = ['title'];
//        $this->view->assign('rec_yn',$this->model->getRcyn());
    }

    public function index($ids=null)
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            $packageModel = new \gyo2o\model\ActivityItem();
            $result = $packageModel->getActivityItem($ids);
            return json($result);
        }

        $this->assign('ids',$ids);
        return $this->view->fetch();
    }

    public function add(){
        $activityid = input('activityid');
        $params = $this->request->post("row/a");
        $params['activity_id'] = $activityid;
        if ($this->request->isPost()) {
            if ($params && $activityid) {
                foreach ($params as $k => &$v) {
                    $v = is_array($v) ? implode(',', $v) : $v;
                }

                $classifyModel = new \gyo2o\model\ActivityItem();
                if ($classifyModel->addItemToActivity($params) !== false) {
                    $this->success();
                } else {
                    $this->error('error');
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }


        return $this->view->fetch();

    }
}