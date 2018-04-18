<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/2 0002
 * Time: 上午 9:40
 */

namespace gyo2o\dao;


use think\Model;

class EmployeeActivityDao extends Model
{
    protected $table = 'tb_employee_activity';
    protected $append = ['draw_name'];


    public function getDrawnameAttr($value,$row){
        $drawDao = new LuckyDrawDao();
        $draw = $drawDao->find($row['draw_id']);
        if($draw){
            return $draw['title'];
        }
        return null;
    }

}