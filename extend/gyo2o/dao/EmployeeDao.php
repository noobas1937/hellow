<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6 0006
 * Time: 下午 2:07
 */

namespace gyo2o\dao;


use think\Model;

class EmployeeDao extends Model
{
    protected $table = 'tb_employee';

    public function get_list($where,$sort,$order,$offset,$limit){
        return $this->where($where)
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->select();
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

    //根据用户ID获取
    public function get_by_id($employeeid){
        if(empty($employeeid)){
            return false;
        }
        return $this->where('id',$employeeid)->find();
    }


    public function get_by_mobile($mobile)
    {
        if (empty($mobile)) {
            return false;
        }
        return $this->where('contact_moblie', $mobile)->find();
    }

    public function insert_points($points,$id){
        return $this->where(['id'=>$id])->setInc('points',$points);

    }

    public function dec_points($points,$id){
        return $this->where(['id'=>$id])->setDec('points',$points);

    }

    public function getBySector($enterpriseid,$sectorid){
        $where['enterprise_id'] = $enterpriseid;
        if(is_numeric($sectorid)){
            $where['sector_id'] = $sectorid;
        }
        return $this->where($where)->column('id');
    }

    public function getByIdcard($idcard){
        return $this->where(['idcard'=>$idcard])->find();
    }

    public function getByUid($uid){
        return $this->where(['tb_user_id'=>$uid])->find();
    }

}