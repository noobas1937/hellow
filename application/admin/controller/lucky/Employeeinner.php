<?php

namespace app\admin\controller\lucky;

use app\common\controller\Backend;

use gyo2o\dao\EmployeeDao;
use think\Controller;
use think\Request;

/**
 * 场内员工
 *
 * @icon fa fa-circle-o
 */
class Employeeinner extends Backend
{
    
    /**
     * TbEmployeeInner模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\\gyo2o\\dao\\EmployeeInnerDao');

    }
    

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);

        if ($this->request->isAjax())
        {
            $luckyDrawRecordModel = new \gyo2o\model\LuckyDrawRecord();
            $limit = $this->request->get('limit');
            $sort = $this->request->get('id');
            $order = $this->request->get('order');
            $offset = $this->request->get('offset');
            $search = $this->request->get('search');
            $employee = new EmployeeDao();
            if(!empty($search)){
                $total = $employee->alias('e')->join(['tb_employee_inner'=>'i'],'i.employee_id = tb_employee.id')
                    ->where('e.name|e.contact_moblie|e.idcard','like','%'.$search.'%')->count();
                $rows = $employee->alias('e')->join(['tb_employee_inner'=>'i'],'i.employee_id = tb_employee.id')
                    ->where('e.name|e.contact_moblie|e.idcard','like','%'.$search.'%')->order($sort,$order)->limit($offset,$limit)->select();
//var_dump($employee->getLastSql());die;
            }else{
                $total = $employee->alias('e')->join(['tb_employee_inner'=>'i'],'i.employee_id = tb_employee.id')->count();
                $rows = $employee->alias('e')->join(['tb_employee_inner'=>'i'],'i.employee_id = tb_employee.id')
                    ->order($sort,$order)->limit($offset,$limit)->select();
            }

            return ['total'=>$total,'rows'=>$rows];
        }

        return $this->view->fetch();
    }


}
