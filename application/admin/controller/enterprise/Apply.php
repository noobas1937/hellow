<?php

namespace app\admin\controller\enterprise;

use app\common\controller\Backend;

use gyo2o\model\EmployeeApply;
use think\Config;
use think\Controller;
use think\Request;

/**
 * 员工申请管理
 *
 * @icon fa fa-circle-o
 */
class Apply extends Backend
{
    
    /**
     * TbEmployeeApply模型对象
     */
    protected $model = null;
    protected $searchFields = ['name','contact_moblie','idcard'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\\gyo2o\\dao\\EmployeeApplyDao');

    }

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {

            $type = !empty($this->request->get('type'))?$this->request->get('type'):0;
            $offset = !empty($this->request->get('offset'))?$this->request->get('offset'):0;
            $limit = !empty($this->request->get('limit'))?$this->request->get('limit'):10;
            $employeeApplayModel = new EmployeeApply();
            $result = $employeeApplayModel->getByAuthStatus($type,$offset,$limit);


//            $result = array("total" => 3, "rows" => $data);
            return json($result);
        }
        return $this->view->fetch();
    }


    public function img($ids){
        $applyModel = new EmployeeApply();
        echo $applyModel->getApplyImg($ids);
    }

    public function check($ids){
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                foreach ($params as $k => &$v)
                {
                    $v = is_array($v) ? implode(',', $v) : $v;

                }
                $employeeApplyModel = new EmployeeApply();
                if($params['type'] == 2){
                    //拒绝
                    $result = $employeeApplyModel->backendRefuse($params);

                }else if($params['type'] == 1){
                    //通过
                    $result = $employeeApplyModel->backendPass($params);
                }
                if ($result !== false) {
                    $this->success('提交成功');
                } else {
                    $this->error($employeeApplyModel->getError());
                }

            }else{
                $this->error(__('Parameter %s can not be empty', ''));
            }

        }
        $this->view->assign('ids',$ids);
        return $this->view->fetch();
    }

    public function excel(){
        Config::set('default_return_type', 'json');
        $file = $this->request->file('file');
        $path = $file->getRealPath();
        $employeeAply = new EmployeeApply();
        $result = $employeeAply->importExcel($path);

        if($result){
            $this->success('导入成功', null,['code'=>100000]);
        }else{
            $this->error($employeeAply->getError(), null);
        }

    }
    

}
