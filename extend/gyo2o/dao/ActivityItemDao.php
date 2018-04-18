<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/11 0011
 * Time: 下午 4:09
 */

namespace gyo2o\dao;


use think\Model;

class ActivityItemDao extends Model
{
    protected $table = 'tb_activity_item';

    public function getByActivityId($id){
        return $this->where('activity_id',$id)->select();
    }
}