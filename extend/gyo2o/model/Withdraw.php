<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/4 0004
 * Time: 下午 3:03
 */

namespace gyo2o\model;


use gyo2o\BaseModel;
use gyo2o\dao\EmployeeDao;
use gyo2o\dao\EmployeeWithdrawDao;

class Withdraw extends BaseModel
{

    public function getWithdrawWaste($where, $sort, $order, $offset, $limit,$id=0){
        $withdrawDao = new EmployeeWithdrawDao();
        $total = $withdrawDao->getTotalByCondition($where,$id);
        if(empty($total)){
            return ['rows'=>[],'total'=>0];
        }
        $rows = $withdrawDao->getByCondition($where,$sort,$order,$offset,$limit,$id);
        array_walk($rows,[$this,'addtionEmployeeInfo']);
        return ['total'=>$total,'rows'=>$rows];
    }

    public function addtionEmployeeInfo(&$row){
        $employeeDao = new EmployeeDao();
        $employee = $employeeDao->find($row['employee_id']);
        $row['idcard'] = $employee['idcard'];
        $row['employee_name'] = $employee['name'];
        $row['contact_moblie'] = $employee['contact_moblie'];
    }

    public function confirm($id){
        $withdrawDao = new EmployeeWithdrawDao();
        return $withdrawDao->confirm($id);
    }
}