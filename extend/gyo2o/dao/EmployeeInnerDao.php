<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/2 0002
 * Time: 上午 10:31
 */

namespace gyo2o\dao;


use think\Model;

class EmployeeInnerDao extends Model
{
    protected $table = 'tb_employee_inner';
    protected $append = ['employee_name'];

    public function getEmployeenameAttr($value,$row){
        $employeeDao = new EmployeeDao();
        $employee = $employeeDao->find($row['employee_id']);
        if(empty($employee)){
            return '';
        }
        return $employee['name'];
    }

    public function getByEmployeeid($userid){
        return $this->where(['employee_id'=>$userid])->find();
    }
}