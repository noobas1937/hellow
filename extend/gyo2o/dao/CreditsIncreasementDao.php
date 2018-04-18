<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/3 0003
 * Time: 下午 3:04
 */

namespace gyo2o\dao;


use think\Model;

class CreditsIncreasementDao extends Model
{
    protected $table = 'tb_credits_increasement';

    public function getByRecordid($recordid){
        return $this->where(['record_id'=>$recordid])->find();
    }

    public function sumFreezenByUserid($userid){
        return $this->where(function($query){$query->whereOr(['isconfirm'=>['<',2],'unfreeze_time'=>['>',date('Y-m-d')]]);})->where(['employee_id'=>$userid])->sum('credits');
    }

    public function sumSalaryByUserid($userid,$type){
        return $this->where(['employee_id'=>$userid,'type'=>$type])->sum('credits');
    }

    public function userConfirm($userid,$recordid){
        return $this->where(['employee_id'=>$userid,'record_id'=>$recordid,'isconfirm'=>0])->setField('isconfirm',2);
    }


    public function hrConfirm($recordid){
        return $this->where(['record_id'=>$recordid,'isconfirm'=>1])->setField('isconfirm',2);
    }

}