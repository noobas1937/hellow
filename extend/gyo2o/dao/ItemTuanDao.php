<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/28 0028
 * Time: 下午 3:09
 */

namespace gyo2o\dao;


use think\Model;

class ItemTuanDao extends Model
{
    protected $table = 'tb_item_tuan';
    const PAY_SUCCESS = 2;
    const CREATE = 0;
    const JOIN = 1;
    const PAY_WAIT = 1;
    const PAY_FAIL = 3;

    /**
     * 根据查询条件获取多条数据
     * @param $where
     *
     * @return mixed
     */
    protected function get_list($where)
    {
        return $this->where($where)->select();
    }

    /**
     * 根据查询条件获取一条条数据
     * @param $where
     *
     * @return mixed
     */
    protected function get_one($where)
    {
        return $this->where($where)->find();
    }

    /**
     * 根据商品id获取尚未失效的拼团信息
     * @Author: fuhaijuan
     * @Date:2016/08/03
     * @param $id
     *
     * @return mixed
     */
    public function get_by_id($id)
    {
        $time = time();
        $map = array(
            'item_id' => $id,
            '_string' =>'UNIX_TIMESTAMP(end_time) >' . $time.' and status = '.self::PAY_SUCCESS.' or (behavior = '.self::JOIN.' and status = '.self::PAY_WAIT.' and UNIX_TIMESTAMP(start_time)+15*60 > '.$time .') ',
            //'_string' =>'status = '.self::PAY_SUCCESS.' or (behavior = '.self::JOIN.' and status = '.self::PAY_WAIT.' and UNIX_TIMESTAMP(start_time)+15*60 > '.$time .') ',
        );
        return $this->get_list($map);
    }

    public function get_by_id_all($id){
        $time = time();
        $map = array(
            'item_id' => $id,
            '_string' =>'UNIX_TIMESTAMP(end_time) <' . $time.' and status = '.self::PAY_SUCCESS,
        );
        return $this->get_list($map);
    }

    /**
     * 根据商品id，开团人锁定某份参团信息
     * @Author: fuhaijuan
     * @Date: 2016/8/3
     *
     * @param $itemId
     * @param $createUid
     *
     * @return mixed
     */
    public function get_info_by_item_create($itemId, $createUid)
    {
        $time = time();
        $map = array(
            'item_id' => $itemId,
            'create_uid' => $createUid,
            //  'end_time' => $endTime,
            //'status' => self::PAY_SUCCESS
            '_string' =>' status = '.self::PAY_SUCCESS.' or (behavior = '.self::JOIN.' and status = '.self::PAY_WAIT.' and UNIX_TIMESTAMP(start_time)+15*60 > '.$time.')'
        );
        return $this->get_list($map);
    }

    /**
     * 根据商品id，用户id，支付信息，是否为开团人
     */
    public function get_item_create_behavior($itemId, $createUid, $pay_status, $behavior)
    {
        $map = array(
            'item_id' => $itemId,
            'create_uid' => $createUid,
            'status' => $pay_status,
            'behavior' => $behavior
        );
        return $this->where($map)->select();
    }

    /**
     * 判断用户当天是否已经团过了
     * @param $uid
     * @param $itemId
     *
     * @return bool
     */
    public function has_tuan($uid, $itemId)
    {
        $time = time();
        $todayStart = date('Y-m-d') . ' 00:00:00';
        $end = date('Y-m-d H:i:s');
        $map = array(
            'tuan_uid' => $uid,
            'item_id' => $itemId,
            'start_time' => array('between', array($todayStart, $end)),
            '_string' =>'UNIX_TIMESTAMP(end_time) >' . $time.' and  status = '.self::PAY_SUCCESS.' or ( status = '.self::PAY_WAIT.' and UNIX_TIMESTAMP(start_time)+15*60 > '.$time.')'
        );
        $result = $this->get_list($map);
        if ($result && is_array($result))
            return true;
        return false;
    }

    /**
     * 修改支付流水号
     * @param $payid
     * @param $newpayid
     *
     * @return bool
     */
    public function set_payno($payid, $newpayid)
    {
        $map = array(
            'pay_no' => $payid,
        );
        $result = $this->where($map)->setField("pay_no", $newpayid);
    }

    /**
     * 根据用户id获取参团信息
     * @Author: fuhaijuan
     * @Date: 2016/8/3
     * $uid int ,$itemid int
     */
    public function get_by_uid($uid, $itemid)
    {
        $time = time();
        $map = array(
            'item_id' => $itemid,
            //'status' => self::PAY_SUCCESS,
            'tuan_uid' => $uid,
//            'end_time'=>array('gt',time()),
            '_string' =>' status = '.self::PAY_SUCCESS.' or ( status = '.self::PAY_WAIT.' and UNIX_TIMESTAMP(start_time)+15*60 > '.$time.')'
        );
        return $this->get_one($map);
    }

    /**
     * 根据支付流水获取信息
     * @Author: fuhaijuan
     * @Date: 2016/8/3
     * $uid int ,$itemid int
     */
    public function get_payid($payid)
    {
        $map = array(
            'pay_no' => $payid
        );
        return $this->get_one($map);
    }

    /**
     * 根据tuan_id获取开团信息
     * @Author: fuhaijuan
     * @Date: 2016/8/3
     * $uid int ,$itemid int
     */
    public function get_behavior($tuan_id)
    {
        $map = array(
            'create_uid' => $tuan_id,
            'behavior' => self::CREATE,
            'status' => self::PAY_SUCCESS
        );
        return $this->get_one($map);
    }

    /**
     * 获取开团的信息
     * @Author: fuhaijuan
     * @Date: 2016/8/3
     * $uid int ,$itemid int
     */
    public function get_creatid($creatid)
    {
        $map = array(
            'behavior' => self::CREATE,
            'create_uid' => $creatid
        );
        return $this->get_one($map);
    }

    /**
     * 获取团支付成功的总数
     * @Author: fuhaijuan
     * @Date: 2016/8/3
     * $uid int ,$itemid int
     */
    public function get_create_count($creatid)
    {
        $map = array(
            'create_uid' => $creatid,
            'status' => self::PAY_SUCCESS
        );
        return $this->where($map)->count();
    }

    /**
     * 根据时间，状态，团id获取团的总数
     */
    public function get_tuan_count($creatid)
    {
        $map = array(
            'create_uid' => $creatid,
            'start_time' => array("gt",date("Y-m-d H:i:s",time()-15*60)),
            'status' => 1
        );
        return $this->where($map)->count();
    }

    /**
     * 根据团id获取所有信息
     * @Author: fuhaijuan
     * @Date: 2016/8/3
     * $uid int ,$itemid int
     */
    public function get_create_all($creatid)
    {
        $map = array(
            'create_uid' => $creatid
        );
        return $this->where($map)->select();
    }

    /**
     * 根据开团用户id获取该份参团信息
     * @Author: fuhaijuan
     * @Date: 2016/8/3
     * $uid int ,$itemid int
     */
    public function get_by_createid($itemid, $createuid)
    {
        $time = time();
        $map = array(
            'item_id' => $itemid,
            'create_uid' => $createuid,
            '_string' =>'UNIX_TIMESTAMP(end_time) >' . $time.' and status = '.self::PAY_SUCCESS.' or ( status = '.self::PAY_WAIT.' and UNIX_TIMESTAMP(start_time)+15*60 > '.$time.')'
        );
        return $this->get_list($map);
    }

    public function set_status($payid, $data)
    {
        if ($payid) {
            $where['pay_no'] = $payid;
            $result = $this->where($where)->save($data);
            return $result;
        } else {
            return false;
        }
    }


    /**
     * 获取团购商品中支付状态为待支付的记录
     * @param $itemId
     * @return mixed
     */
    public function get_wait_pay_by_item($itemId){
        $map = array(
            'item_id'=>$itemId,
            'status'=>self::PAY_WAIT
        );
        return $this->get_list($map);
    }

    public function get_wait_pay_by_user($userId,$itemId){
        $map = array(
            'status'=>self::PAY_WAIT,
            'item_id'=>$itemId,
            'tuan_uid'=>$userId
        );
        return $this->get_one($map);
    }


}