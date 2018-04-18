<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6 0006
 * Time: 下午 2:07
 */

namespace gyo2o\dao;


use think\Model;

class CreditsRecordDao extends Model
{
    protected $table = 'tb_credits_record';

    public function add_record($map){
        $map['create_date'] = date('Y-m-d H:i:s');
        return $this->insert($map);
    }

    public function get_list($where,$sort,$order,$offset,$limit,$ids = null){
        $data = $this->where($where);
        if($ids && $ids > 0){
            $data->where(['tb_employee_id'=>$ids]);
        }
        $data->order($sort, $order)
            ->limit($offset, $limit);
        $result = $data->select();

        return $result;
    }

    public function add($data){
        return $this->insertGetId($data);
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
        $map = ['id' => $id];
        return $this->where($map)->find();
    }
}