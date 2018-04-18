<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6 0006
 * Time: 下午 2:07
 */

namespace gyo2o\dao;


use think\Model;

class SectorDao extends Model
{
    protected $table = 'tb_sector';

    public function get_list($where,$sort,$order,$offset,$limit){
        return $this->where($where)
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->select();
    }

    public function add($data){
        return $this->insert($data);
    }

    public function get_all($pkey_value = null){
        $map = [];
        if($pkey_value){
            $map = [
                'id' => $pkey_value
            ];
        }
        return $this->where($map)->select();
    }

    public function get_company_all($enterprise_id){
        $map = [
            'enterprise_id' => $enterprise_id
        ];
        return $this->where($map)->select();
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