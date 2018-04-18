<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/18 0018
 * Time: 上午 11:17
 */

namespace gyo2o\dao;


use think\Model;

class UserAccountDao extends Model
{

    protected $table = "tb_user_account";
    protected $hidden = ['password'];


    public function get_by_openid($wx_openid)
    {
        $where['wx_openid'] = $wx_openid;

        return $this->where($where)->find();
    }

    public function get_by_alipay_openid($alipay_openid)
    {
        $where['alipay_openid'] = $alipay_openid;

        return $this->where($where)->find();
    }

    public function get_by_mobile($mobile)
    {
            $where['mobile'] = $mobile;
        return $this->where($where)->find();
    }

    public function get_by_id($userId)
    {
        if ($userId)
            $where['id'] = $userId;
        return $this->where($where)->find();
    }

    public function set_mobile($userid, $mobile)
    {
        if ($userid)
            $where['id'] = $userid;
        $result = $this->where($where)->setField("mobile", $mobile);
        return $result;
    }

    public function set_openid($map, $data)
    {
        $result = $this->update($data,$map);
        return $result;
    }
}