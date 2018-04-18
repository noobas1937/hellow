<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/28 0028
 * Time: 下午 3:09
 */

namespace gyo2o\dao;


use think\Model;

class UserAddressDao extends Model
{
    protected $table = "tb_user_address";

    public function get_by_default($userid)
    {
        if ($userid)
            $where['user_id'] = $userid;
        $where['is_default'] = 1;
        return $this->where($where)->find();
    }

    public function get_by_id($addressid)
    {

            $where['id'] = $addressid;
        return $this->where($where)->find();
    }

    public function get_by_uid($userid,$page,$page_size)
    {
            $where['user_id'] = $userid;

        return $this->where($where)->order("is_default desc")->page($page)->paginate($page_size)->toArray();
    }

    public function set_default($userid)
    {

            $where['user_id'] = $userid;
        return $this->where($where)->setField("is_default", 0);
    }

    public function set_id($id, $user_id, $data)
    {
        $where = [
            'id' => $id,
            'user_id' => $user_id
        ];
        return $this->save($data,$where);
    }

    public function del_id($id,$user_id)
    {
        $where = [
            'id' => $id,
            'user_id' => $user_id
        ];
        return $this->where($where)->delete();
    }


    public function add_address($data)
    {
        $data['create_date'] = date("Y-m-d H:i:s");
        return $this->insertGetId($data);
    }
}