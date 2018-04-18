<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6 0006
 * Time: 下午 2:07
 */

namespace gyo2o\dao;


use think\Model;

class IdentityDao extends Model
{
    protected $table = 'tb_identity';

    public function get_all($pkey_value = null)
    {
        $map = [];
        if($pkey_value){
            $map['id'] = $pkey_value;
        }
        return $this->where($map)->select();
    }

    public function get_value($identity_id){
        return $this->where(['id' => $identity_id])->value('name');
    }
}