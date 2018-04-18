<?php

namespace app\admin\controller\dishes;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 套餐详情管理
 *
 * @icon fa fa-circle-o
 */
class Packagedetail extends Backend
{
    
    /**
     * Packagedetail模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('gyo2o\dao\PackagedetailDao');
        $this->modelValidate = true;
        $this->modelSceneValidate = true;
    }
    


    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $id = input('ids');
        if ($this->request->isAjax())
        {

            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }

            $packagedetail = new \gyo2o\model\Packagedetail();
            $result = $packagedetail->getPackagedetail($id);




            return json($result);
        }
        $this->view->assign('id',$id);
        return $this->view->fetch();
    }


    public function add()
    {
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            $packageid = input('ids');
            $params['package_id'] = $packageid;
            if ($params)
            {
                foreach ($params as $k => &$v)
                {
                    $v = is_array($v) ? implode(',', $v) : $v;
                }
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
