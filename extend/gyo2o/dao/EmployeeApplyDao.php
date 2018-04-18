<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/28 0028
 * Time: 下午 3:09
 */

namespace gyo2o\dao;


use think\Model;

class EmployeeApplyDao extends Model
{
    protected $table = "tb_employee_apply";

    public function add_apply($params){
        return $this->allowField(true)->insert($params);
    }

    public function getByUserId($userid){
        return $this->where(['tb_user_id'=>$userid])->find();
    }
}