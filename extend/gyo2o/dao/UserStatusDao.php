<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/28 0028
 * Time: 下午 3:09
 */

namespace gyo2o\dao;


use think\Model;

class UserStatusDao extends Model
{
    protected $table = "tb_user_status";

    public function get_by_userid($userid)
    {
        $where['user_id'] = $userid;
        $result = $this->where($where)->find();
        return $result;
    }

    public function set_home_health($status, $user_id)
    {
        $where['user_id'] = $user_id;
        return $this->where($where)->setField('home_health', $status);
    }

    public function set_userid($userid,$data){
        $where['user_id'] = $userid;
        $data['set_time'] = time();
        return $this->save($data,$where);
    }
}