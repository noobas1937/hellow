<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6 0006
 * Time: 下午 2:07
 */

namespace gyo2o\dao;


use think\Model;

class CartDao extends Model
{
    protected $table = 'tb_cart';


    public function add_cart($user_id,$num,$item_id){
        $map = [
            'create_date' => date('Y-m-d H:i:s'),
            'number' => $num,
            'user_id' => $user_id,
            'item_id' => $item_id,
        ];
        return $this->insert($map);
    }

    public function add_num($user_id,$item_id){
        $map = [
            'user_id' => $user_id,
            'item_id' => $item_id,
        ];
        return $this->where($map)->setInc('number');
    }

    public function set_num($user_id,$item_id,$num){
        $map = [
            'user_id' => $user_id,
            'item_id' => $item_id,
        ];
        return $this->save([ 'number' => $num],$map);
    }

    public function del_cart($user_id,$item_id){
        $map = [
            'user_id' => $user_id,
            'item_id' => $item_id,
        ];
        return $this->where($map)->delete();
    }

    public function get_info($user_id,$item_id){
        $map = [
            'user_id' => $user_id,
            'item_id' => $item_id,
            'del_flag' => 0
        ];
        return $this->where($map)->find();
    }

    public function get_by_user_id($user_id, $page = 1, $page_size = 5)
    {
        $map = [
            'user_id' => $user_id,
            'del_flag' => 0
        ];
        return $this->where($map)->order("create_date desc")->page($page)->paginate($page_size)->toArray();
    }

    public function set_flag($id, $flag)
    {
            $where['id'] = $id;
        return $this->where($where)->setField("del_flag", $flag);
    }

    public function get_by_id($id)
    {
        if ($id)
            $where['id'] = $id;
        $where['del_flag'] = 0;
        return $this->where($where)->find();
    }
}