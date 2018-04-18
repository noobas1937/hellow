<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/3 0003
 * Time: 下午 6:44
 */

namespace gyo2o\dao;


use think\Model;

class CreditsReduceDao extends Model
{
    protected $table = 'tb_credits_reduce';

    public function getByRecordid($userid){
        return $this->where(['record_id'=>$userid])->find();
    }

}