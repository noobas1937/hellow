<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/28 0028
 * Time: 下午 3:09
 */

namespace gyo2o\dao;


use think\Model;

class OrderStatusDao extends Model
{
    protected $table = 'tb_order_status';
    const UN_DEL = 0;

    public function get_sn2_status($sn2,$status){
        $map = array(
            'sn2'=>$sn2,
            'status'=>$status
        );
        return $this->where($map)->order("update_date desc")->select();
    }

    public function get_last($sn2,$status){
        $map = array(
            'sn2'=>$sn2,
            'status'=>$status
        );
        return $this->where($map)->order("update_date desc")->find();
    }

    public function getByOrderidAndRemark($orderid,$remark){
        $map = array(
            'order_id'=>$orderid,
            'remark' => $remark
        );
        return $this->where($map)->order('update_date desc')->find();
    }

    public function getById($id){
        return $this->where('order_id',$id)->order('update_date desc')->select();
    }
}