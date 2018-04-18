<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/9 0009
 * Time: 下午 2:47
 */

namespace gyo2o\dao;


use think\Model;

class CredisIncreasementDetailDao extends Model
{
    protected $table = 'tb_credits_increasement_detail';

    public function getByRecordid($recordid){
        return $this->where(['record_id'=>$recordid])->find();
    }

    public function getByIdcard($idcard,$year,$month){
        return $this->where(['tb_year'=>$year,'tb_month'=>$month,'F'=>$idcard])->find();
    }

}