<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/28 0028
 * Time: 下午 4:51
 */

namespace gyo2o\dao;


use think\Model;

class OrderDao extends Model
{
    // 表名
    protected $table = 'tt_order';
    const UN_DEL = 0;

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    // 追加属性
    protected $append = [
        'dishes_type_text',
        'order_status_text'
    ];



    public function getDishestypelist()
    {
        return ['A' => __('A'),'B' => __('B'),'C' => __('C'),'Z' => __('Z')];
    }

    public function getOrderstatuslist()
    {
        return ['待领' => __('待领'),'已领' => __('已领')];
    }


    public function getDishestypeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['dishes_type'];
        $list = $this->getDishestypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getOrderstatusTextAttr($value, $data)
    {
        $value = $value ? $value : $data['order_status'];
        $list = $this->getOrderstatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    //骑手点餐信息
    public function getOrderByRider($where){
        return $this->where($where)->select();
    }

    public function getOrderByDate($date,$riderid){
        return $this->where("date='$date' and rider_id=$riderid")->find();
    }

    public function getReceiveOrder($dates,$riderid){
        return $this->where('date','in',$dates)->where("rider_id=$riderid")->select();
    }

    public function editOrder($riderid,$date,$dishesType){
        $order = $this->where("date='$date' and rider_id=$riderid")->find();
        if(empty($order)){
            return false;
        }
        $order->dishes_type = $dishesType;
        return $order->save();
    }

    public function getNextWeekOrder($riderid){
        $nex_monday = date('Y-m-d',strtotime('+1 monday',time()));
        return $this->where("rider_id=$riderid and date>='$nex_monday'")->select();
    }


}