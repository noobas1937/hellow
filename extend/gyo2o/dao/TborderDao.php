<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6 0006
 * Time: 下午 2:07
 */

namespace gyo2o\dao;


use think\Model;

class TborderDao extends Model
{
    protected $table = 'tb_order';
    protected $append = ['status_text'];
    private $status = ['-1'=>'取消','0'=>'待付款','1'=>'待确认','2'=>'待评价','3'=>'已完成','4'=>'退款中','5'=>'退款成功','6'=>'退款失败'];
    public function getStatustextAttr($value,$data){
        if($data['status'] == 1){
            $orderStatusDao = new OrderStatusDao();
            $orderStatus = $orderStatusDao->get_sn2_status($data['sn2'],$data['status']);
            if($orderStatus){
               return $orderStatus[0]['remark'];
            }
        }
        return $this->status[$data['status']];
    }
    const UN_DEL = 0;

    /**
     * 获取商品销售量
     * @param $id
     * @return mixed
     */
    public function count_item_sale($id)
    {
        $map = array(
            'status' => array('gt', 0),
            'del_flag' => 0,
                'item_id' => $id
        );
        return $this->where($map)->sum('item_num');
    }

    public function getByItemid($where, $sort, $order, $offset, $limit,$itemid){
        return $this->where($where)->where('item_id',$itemid)->where('del_flag',0)->order($sort,$order)->limit($offset,$limit)->select();
    }

    public function getTotalByItemid($where,$itemid){
        return $this->where($where)->where('item_id',$itemid)->where('del_flag',0)->count();
    }

    public function getOrders($where, $sort, $order, $offset, $limit)
    {
        return $this->where($where)->where('del_flag',0)->order($sort,$order)->limit($offset,$limit)->select();
    }

    public function getTotal($where)
    {
        return $this->where($where)->where('del_flag',0)->count();
    }

    public function setStatus($orderid,$status){
        return $this->where('id',$orderid)->setField('status',$status);
    }


    /**
     * 获取订单
     */
    public function get_by_userid($userid)
    {
        $map = array(
            'del_flag' => self::UN_DEL,
            'user_id' => $userid
        );
        $data = $this->where($map)->group("sn1")->order('create_date desc')->select();
        return $data;
    }


    public function get_list($user_id,$first=1,$last=5)
    {
        $map = array(
            'del_flag' => self::UN_DEL,
            'user_id' => $user_id
        );

        $list = $this->where($map);
        $data = $list->order('create_date desc,delivery_date2 asc')->page($first)->paginate($last)->toArray();
        return $data;
    }

    /**
     * 根据状态获取订单
     */
    public function get_by_status($userid, $status,$first=1,$last=5)
    {
        $map = array(
            'del_flag' => self::UN_DEL,
            'user_id' => $userid,
            'status' => $status,
            'after_sales' => 0
        );
        $list = $this->where($map);
        $data = $list->order('create_date desc,delivery_date2 asc')->page($first)->paginate($last)->toArray();
        return $data;
    }

    /**
     * 根据订单号获取支付流水
     */
    public function get_order_no($order_no)
    {
        $map = array(
            "sn1" => $order_no
        );
        return $this->where($map)->find();
    }

    /**
     * 根据子订单号获取支付流水
     */
    public function get_sn2($order_no)
    {
        $map = array(
            'del_flag' => self::UN_DEL,
            "sn2" => $order_no
        );
        return $this->where($map)->find();
    }

    /**
     * 根据状态获取订单数量
     */
    public function get_status_count($status,$userid)
    {
        $map = array(
            'del_flag' => self::UN_DEL,
            "status" => $status,
            'user_id'=> $userid
        );
        return $this->where($map)->group('sn2')->count();
    }

    /**
     * 根据子订单号修改状态
     */
    public function set_sn2($sn2,$status)
    {
        $map = array(
            'sn2' => $sn2
        );
        $data = $this->where($map)->setField("status", $status);
        return $data;
    }

    /**
     * 根据订单号获取所有订单
     */
    public function get_order_no_all($order_no, $group = null)
    {
        $map = array(
            "sn1" => $order_no
        );
        $order = $this->where($map);
        if ($group != null) {
            $order->group($group);
        }
        return $order->select();
    }

    /**
     * 拼团成功后修改配送时间
     */
    public function set_delivery_date($payid, $delivery_date)
    {
        $map = array(
            'pay_no' => $payid
        );
        $data = $this->where($map)->setField("delivery_date2", $delivery_date);
        return $data;
    }

    /**
     * 根据支付流水号修改删除状态
     */
    public function set_del_flag($payid, $del_flag,$status = null)
    {
        $map['pay_no'] = $payid;
        if($status != null){
            $map['status'] = $status;
        }
        $data = $this->where($map)->setField("del_flag", $del_flag);
        return $data;
    }

    /**
     * 根据订单号修改删除状态
     */
    public function set_order_no($order_no, $del_flag)
    {
        $map = array(
            'sn1' => $order_no
        );
        $data = $this->where($map)->setField("del_flag", $del_flag);
        return $data;
    }

    /**
     * 根据订单号修改支付状态
     */
    public function set_orderid_status($order_no, $status)
    {
        $map = array(
            'sn1' => $order_no
        );
        $data = $this->where($map)->setField("status", $status);
        return $data;
    }

    public function get_by_payno($payid)
    {
        $map = array(
            'del_flag' => self::UN_DEL,
            'pay_no' => $payid
        );
        return $this->where($map)->group("item_id")->select();
    }

    public function get_by_payno_all($payid)
    {
        $map = array(
            'del_flag' => self::UN_DEL,
            'pay_no' => $payid
        );
        return $this->where($map)->order("delivery_date2 asc")->select();
    }

    public function get_by_payno_one($payid)
    {
        $map = array(
            'del_flag' => self::UN_DEL,
            'pay_no' => $payid
        );
        return $this->where($map)->find();
    }

    public function set_payid($order_no, $payid)
    {
        $map = array(
            'sn1' => $order_no
        );
        return $this->where($map)->setField("pay_no", $payid);
    }

    public function set_status($payid, $status)
    {
        $map = array(
            'pay_no' => $payid
        );
        return $this->where($map)->setField("status", $status);
    }

    public function set_data($id, $status)
    {
        $map = array(
            'id' => $id
        );
        return $this->update(['status' => $status],$map);
    }

    public function set_update($id, $data)
    {
        $map = array(
            'id' => $id
        );
        return $this->update($data,$map);
    }

    /**
     * 获取新客专享商品的状态
     * @param $itemId
     * @return mixed
     */
    public function get_by_item($itemId,$userId){
        $map = array(
            'item_id'=>array('in',join(',',$itemId)),
            'user_id'=>$userId,
            'del_flag'=>self::UN_DEL
        );$this->where($map)->select();
        return $this->where($map)->select();
    }

    public function getByOrderId($id){
        return $this->where('id',$id)->find();
    }
}