<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/28 0028
 * Time: 下午 3:09
 */

namespace gyo2o\dao;


use think\Model;

class EmployeeBankDao extends Model
{
    protected $table = "tb_employee_bank";

    public function add_bank($params){
        return $this->allowField(true)->insert($params);
    }
}