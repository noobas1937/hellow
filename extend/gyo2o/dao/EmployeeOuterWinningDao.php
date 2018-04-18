<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/6 0006
 * Time: 上午 10:52
 */

namespace gyo2o\dao;


use think\Model;

class EmployeeOuterWinningDao extends Model
{
    protected $table = 'tb_employee_outer_winning';

    public function getByMobile($mobile){
        return $this->where(['mobile'=>$mobile])->find();
    }

}