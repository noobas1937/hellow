<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/28 0028
 * Time: 下午 1:39
 */

namespace gyo2o\dao;


use think\Model;

class CreditsRecodDao extends Model
{

    protected $table = 'tb_credits_record';

    public function countByCondition($where,$id=0){
        if(empty($id)){
            return $this->where($where)->where(['credits'=>['>',0]])->count();
        }else{
            return $this->where($where)->where(['tb_employee_id'=>$id])->count();
        }

    }

    public function getByCondition($where, $sort, $order, $offset, $limit,$id = 0){
        if(empty($id)){
            return $this->where($where)->where(['credits'=>['>',0]])->order($sort,$order)->limit($offset,$limit)->select();
        }else{
            return $this->where($where)->where(['tb_employee_id'=>$id])->order($sort,$order)->limit($offset,$limit)->select();
        }

    }

    public function sumByUserid($userid){
        return $this->where(['tb_employee_id'=>$userid])->sum('credits');
    }

    public function getByUserid($userid,$page,$pagesize){
        return $this->where(['tb_employee_id'=>$userid])->order('create_date','desc')->limit($pagesize*$page,$pagesize)->select();
    }

}