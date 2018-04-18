<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/1 0001
 * Time: 下午 4:36
 */

namespace gyo2o\dao;


use think\Model;

class EmployeeWithdrawDao extends Model
{

    protected $table = 'tb_employee_withdraw';

    public function sumBalanceByUserid($userid){
        return $this->where(['employee_id'=>$userid])->sum('money');
    }

    public function withdrawRecord($userid,$page,$pagesize){
        return $this->where(['employee_id'=>$userid,'money'=>['<',0]])->order('create_time','desc')->limit($page*$pagesize,$pagesize)->select();
    }

    public function getTotalByCondition($where,$id =0){
        if(empty($id)){
            return $this->where($where)->where(['money'=>['<',0]])->count();
        }else{
            return $this->where($where)->where(['employee_id'=>$id])->count();
        }

    }

    public function getByCondition($where,$sort,$order,$offset,$limit,$id=0){
        if(empty($id)){
            return $this->where($where)->where(['money'=>['<',0]])->order('status','asc')->order($sort,$order)->limit($offset,$limit)->select();
        }else{
            return $this->where($where)->where(['employee_id'=>$id])->order($sort,$order)->limit($offset,$limit)->select();
        }

    }

    public function confirm($id){
        return $this->where(['id'=>$id])->setField('status',1);
    }
}