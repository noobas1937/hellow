<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6 0006
 * Time: 下午 2:07
 */

namespace gyo2o\dao;


use think\Model;

class EnterpriseDao extends Model
{
    protected $table = 'tb_enterprise';

    public function get_list($where,$sort,$order,$offset,$limit){
        return $this->where($where)
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->select();
    }

    public function add($data){
        return $this->insert($data);
    }

    public function get_all(){
        return $this->select();
    }

    public function edit($id,$data){
        $map = ['id' => $id];
        return $this->save($data,$map);
    }

    public function get_count($where){
        return $this->where($where)->count();
    }

    public function del($id){
        $map = ['id' => $id];
        return $this->where($map)->delete();
    }

    public function get_id($id){
        $map = ['id' => ['in',$id]];
        return $this->where($map)->find();
    }

}