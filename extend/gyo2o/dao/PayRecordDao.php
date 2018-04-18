<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6 0006
 * Time: 下午 2:07
 */

namespace gyo2o\dao;


use think\Model;

class PayRecordDao extends Model
{
    protected $table = 'tb_pay_record';

    public function get_by_payno($pay_no)
    {
        $where = array("pay_no" => $pay_no, "del_flag" => 0);
        $result = $this->where($where)->find();
        return $result;
    }


    public function set_status($payid, $status)
    {

            $where['pay_no'] = $payid;
        $result = $this->where($where)->setField("status", $status);
        return $result;
    }

}