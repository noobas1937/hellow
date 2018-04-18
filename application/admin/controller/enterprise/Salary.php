<?php

namespace app\admin\controller\enterprise;

use app\common\controller\Backend;

use gyo2o\model\SalaryFormHead;
use think\Controller;
use think\Request;
use think\Config;

/**
 * 员工工资管理
 *
 * @icon fa fa-circle-o
 */
class Salary extends Backend
{
    
    /**
     * TbEmployeeSalary模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
//        $this->model = model('\\gyo2o\\dao\\EmployeeSalaryDao');
        $this->view->assign('type',['1'=>'工资','2'=>'奖励']);

    }
    
    public function excel(){
        Config::set('default_return_type', 'json');
        $file = $this->request->file('file');
        $type = input('type');
        $path = $file->getRealPath();
        $riderModel = new \gyo2o\model\Salary();
        $year = date('Y');
        $month = date('m',strtotime('-m'));
        $result = $riderModel->importExcel($path,$year,$month);

        if($result){
            $this->success('导入成功', null,['code'=>100000]);
        }else{
            $this->success('导入失败', null,['code'=>100001,'msg'=>$riderModel->getError()]);
        }

    }

    public function add(){
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                foreach ($params as $k => &$v)
                {
                    $v = is_array($v) ? implode(',', $v) : $v;

                }
                if(empty($params['employee_id'])|| empty($params['credits'])){
                    $this->error('必填项不能为空');
                }
                $salaryModel = new \gyo2o\model\Salary();
                $result = $salaryModel->creditsIncreasement($params);
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error($salaryModel->getError());
                }
            }else{
                $this->error(__('Parameter %s can not be empty', ''));
            }

        }
        return $this->view->fetch();
    }

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $salaryModel = new \gyo2o\model\Salary();

            $result = $salaryModel->getSalaryWaste($where, $sort, $order, $offset, $limit);

            return json($result);
        }
        return $this->view->fetch();
    }

    public function edit($ids = NULL)
    {
        $salaryModel = new \gyo2o\model\Salary();
        $row = $salaryModel->getOne($ids);
        if($row === false)
            $this->error('不可以修改',url('enterprise/salary'));

        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                foreach ($params as $k => &$v)
                {
                    $v = is_array($v) ? implode(',', $v) : $v;
                }
                $params['id'] = $ids;
                $result = $salaryModel->edit($params);
                if($result){
                    $this->success('修改成功');
                }else{
                    $this->error('出错');
                }

            }

        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    public function hrConfirm($id){
        $withdrawDao = new \gyo2o\model\Salary();
        $result = $withdrawDao->hrConfirm($id);
        if($result){
            $this->success();
        }else{
            $this->error();
        }
    }

}
