<?php

namespace app\admin\controller\lucky;

use app\common\controller\Backend;

use gyo2o\dao\EmployeeActivityDao;
use think\Controller;
use think\Request;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Drawtime extends Backend
{
    
    /**
     * TbNewyearDrawtime模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\\gyo2o\\dao\\NewYearDrawTimeDao');

    }

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $ids = input('ids');
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            $employActivityModel = new EmployeeActivityDao();
            $draw = $employActivityModel->find($ids);
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)
                ->where(['draw_id'=>$draw])
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
                ->where(['draw_id'=>$draw['draw_id']])
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        $this->view->assign('ids',$ids);
        return $this->view->fetch();
    }

    public function add(){
        $id = input('ids');
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                foreach ($params as $k => &$v)
                {
                    $v = is_array($v) ? implode(',', $v) : $v;

                }
                $employActivityModel = new EmployeeActivityDao();
                $draw = $employActivityModel->find($id);
                $params['draw_id'] = $draw['draw_id'];
                if ($this->dataLimit)
                {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                try
                {
                    //是否采用模型验证
                    if ($this->modelValidate)
                    {
                        $name = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : true) : $this->modelValidate;
                        $this->model->validate($validate);
                    }
                    $result = $this->model->save($params);
                    if ($result !== false)
                    {
                        $this->success();
                    }
                    else
                    {
                        $this->error($this->model->getError());
                    }
                }
                catch (\think\exception\PDOException $e)
                {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }


}
