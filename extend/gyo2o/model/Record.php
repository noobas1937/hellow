<?php
namespace gyo2o\model;

use gyo2o\BaseModel;
use gyo2o\dao\CartDao;
use gyo2o\dao\ItemDao;
use gyo2o\dao\PayRecordDao;

class Record extends BaseModel
{

    /**
     * 购物车点击结算
     * @param $param
     * @return array
     */
    public function order_confirm($param)
    {

        $param = is_array($param) ? $param : json_decode($param,true);
        $param['cart'] = json_decode($param['cart'],true);
        $pay_id = isset($param['pay_id']) ? $param['pay_id'] : null;
        if (!$pay_id) {
            $cart = new CartDao();
            $shopping = isset($param['cart']['shopping']) ? $param['cart']['shopping'] : null;
            if (!$shopping) {
                $item = new ItemDao();
                foreach ($shopping as $k => $v) {
                    $item_data = $item->get_bt_item_id($v['item_id']);
                    if($item_data['city_id'] != $shopping['cart']['city_id']){
                        return $item_data['title'].'暂时缺货';
                    }
                    if(isset($param['item_id']) && $param['item_id']> 0){
                        $result = 1;
                    }else{
                        $result = $cart->get_by_id($v['id']);
                    }
                    if (!$result) {
                        return '数据错误';
                    }
                }
            }
            $pay_record = new PayRecordDao();
            $pay_id = getIdentifier();
            $pay_data['user_id'] = $param['user_id'];

            $pay_data['total_money'] = $param['cart']['priceSum'];
            $total_money = $pay_data['total_money'];
            $freight = isset($pay_data['freight']) ? $pay_data['freight'] : false;
            if($total_money < self::FREEFREIGHT){
                $freight = self::FREIGHT;
            }

            $pay_data['pay_no'] = $pay_id;
            $pay_data['create_date'] = date("Y-m-d H:i:s");
            $add_pay_no = $pay_record->insert($pay_data);
            if ($add_pay_no) {
                if(isset($param['cart']['priceSum']) && $param['cart']['priceSum']){
                    $json = json_encode($param);
                    session($pay_id, $json);
                }
                $array = array(
                    "pay_id" => $pay_id,
                    'freight' => $freight
                );
                return $array;
            }
        } else {
            if (session($pay_id) == '') {
                $json = json_encode($param);
            } else {
                $data = json_decode(session($pay_id), true);

                if ($data['cart'] != $param['cart'] && $param['cart'] != '') {
                    $data['cart'] = $param['cart'];
                }
                if ($data['address_id1'] != $param['id'] && $param['id'] != '' && $param['type'] == 1) {
                    $data['address_id1'] = $param['id'];
                }
                if ($data['address_id2'] != $param['id'] && $param['id'] != '' && $param['type'] == 2) {
                    $data['address_id2'] = $param['id'];
                }
                if ($data['time_slot1'] != $param['delivery'] && $param['delivery'] != '' && $param['type'] == 1) {
                    $data['time_slot1'] = $param['delivery'];
                }
                if ($data['time_slot2'] != $param['delivery'] && $param['delivery'] != '' && $param['type'] == 2) {
                    $data['time_slot2'] = $param['delivery'];
                }
                if (($data['date1'] != $param['date1'] || $data['date2'] != $param['date2']) && ($param['date2'] != '' || $param['date1'] != '')) {
                    $data['date1'] = $param['date1'];
                    $data['date2'] = $param['date2'];
                }
                $json = json_encode($data);
            }
            session($pay_id, $json);
            $array = array(
                "pay_id" => $pay_id,
            );
            return $array;
        }
    }
}