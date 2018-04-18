<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/8 0008
 * Time: 上午 11:15
 */

namespace gyo2o\model;


use gyo2o\BaseModel;
use gyo2o\dao\attachmentDao;
use gyo2o\dao\ItemBoxDao;
use gyo2o\dao\ItemImgDao;
use gyo2o\dao\ItemSaleRecordDao;
use gyo2o\dao\ItemTuanDao;
use gyo2o\dao\OrderDao;
use gyo2o\dao\OrderStatusDao;
use gyo2o\dao\PayRecordDao;
use gyo2o\dao\TborderDao;
use gyo2o\dao\ItemDao;

class Tborder extends BaseModel
{
    public function getOrders($where, $sort, $order, $offset, $limit)
    {
        $tborderDao = new TborderDao();
        $total = $tborderDao->getTotal($where);
        if(empty($total)){
            return ['total'=>0,'rows'=>[]];
        }
        $rows = $tborderDao->getOrders($where,$sort,$order,$offset,$limit);
        array_walk($rows,[$this,'addtionField']);

        return ['total'=>$total,'rows'=>$rows];

    }

    /**
     * 支付订单
     */
    public function order_pay($post)
    {
        $pay_record = new PayRecordDao();
        $order = new TborderDao();
        $tuan = new ItemTuanDao();
        $item = new ItemDao();
        $payid = getIdentifier();
        $order_data = $order->get_order_no($post['order_no']);
        $tuan_data = $tuan->get_payid($order_data['pay_no']);
        if ($tuan_data) {
            $behavior = $tuan->get_behavior($tuan_data['create_uid']);
            if ($behavior['end_time'] < date("Y-m-d H:i:s") && $behavior == true) {
                return '团购已过期';
            }
            $tuan_count = $tuan->get_create_count($tuan_data['create_uid']);
            $item_data = $item->get_bt_item_id($tuan_data['item_id']);
            if ($tuan_count >= $item_data['tuan_num']) {
                return '已满团';
            }
            $url = "/service/wxpay/notify_tuan";
            $tuan->set_payno($order_data['pay_no'], $payid);
        } else {
            $url = "/service/wxpay/notify";
        }
        $total_money = $post['money'];
        $pay_data['pay_no'] = $payid;
        $pay_data['user_id'] = $post['user_id'];
        $pay_data['total_money'] = $total_money;
        $pay_data['freight'] = $post['freight'];
        $pay_data['create_date'] = date('Y-m-d H:i:s');
        $add_pay_record = $pay_record->insert($pay_data);
        if ($add_pay_record) {
            $order_data = $order->set_payid($post['order_no'], $payid);
            if ($order_data) {
                $tt_order = new Order();
                $price = ($post['money']+$post['freight'])*100;
                if($post['client'] == 'wx'){
                    $array = $tt_order->wx_pay($payid, $url,$price,$post['user_id']);
                }elseif($post['client'] == 'alipay'){
                    $array = $tt_order->alipay($payid, $post['money']+$post['freight']);
                }

                return $array;
            }
        }
    }

    //根据商品ID获取订单
    public function getOrdersByItemid($where, $sort, $order, $offset, $limit,$itemid){
        $tborderDao = new TborderDao();
        $total = $tborderDao->getTotalByItemid($where,$itemid);
        if(empty($total)){
            return ['total'=>0,'rows'=>[]];
        }
        $rows = $tborderDao->getByItemid($where, $sort, $order, $offset, $limit,$itemid);
        array_walk($rows,[$this,'addtionItemTitls']);
        return ['total'=>$total,'rows'=>$rows];

    }

    //添加商标题
    public function addtionItemTitls(&$order){
        $itemDao = new ItemDao();
        $order['title'] = $itemDao->where('id',$order['item_id'])->value('title');

    }

    //订单添加字段
    public function addtionField(&$order)
    {
        $itemDao = new ItemDao();
        $item = $itemDao->getById($order['item_id']);
        $order['title'] = $item['title'];
        $order['units'] = $item['units'];

    }

    //修改订单状态
    public function setOrderStatus($param){
        $tbOrderDao = new TborderDao();
        return $tbOrderDao->setStatus($param['orderid'],$param['status']);
    }


    public function get_list($param)
    {
        $order = new TborderDao();
        $result = $order->get_list($param['user_id'],$this->page,$this->page_size);
        $result['data'] = $this->data_info($result['data']);
        return $result;
    }

    private function data_info($result){
        $record = new PayRecordDao();
        $order_status = new OrderStatusDao();
        $item = new ItemDao();
        $item_img = new ItemImgDao();
        $itemtuan = new ItemTuanDao();
        $tt_order = new Order();
        $att = new attachmentDao();
        foreach ($result as $key => $val) {
            if($val['delivery_date2'] == true){
                $date_times = strtotime($val['delivery_date2']);
                $week = $tt_order->weekday(date("w",$date_times));
                $result[$key]['delivery_date'] = date('Y年n月j日',$date_times)."周($week)";
            }
            $result[$key]['item'] = $item->get_bt_item_id($val['item_id']);
            $img = $item_img->get_item_cover($result[$key]['item']['product_id']);
            $result[$key]['item']['img_url'] = $att->getUrlAttr($img['img_id']);
            $result[$key]['payid'] = $record->get_by_payno($val['pay_no']);
            $result[$key]['tuan'] = $itemtuan->get_payid($val['pay_no']);
            $result[$key]['tuan']['pay_nm'] = $itemtuan->get_create_count($result[$key]['tuan']['create_uid']);
            $result[$key]['record'] = $record->get_by_payno($val['pay_no']);

            if($val['status'] == 1 || $val['status'] == 4){
                $result[$key]['order_status'] = $order_status->get_last($val['sn2'], $val['status']);
            }
            if($val['money'] < self::FREEFREIGHT && $val['status'] == 0 && $result[$key]['item']['tuan_num'] <= 1){
                $result[$key]['freight'] = self::FREIGHT;
                $result[$key]['count_money'] = $result[$key]['money']+self::FREIGHT;
            }else{
                $result[$key]['count_money'] = $result[$key]['money']+$result[$key]['record']['freight'];
            }

        }
        return $result;
    }


    public function get_order_status($param)
    {
        $order = new TborderDao();
        $result = $order->get_by_status($param['user_id'],$param['status'],$this->page,$this->page_size);
        $result['data'] = $this->data_info($result['data']);
        return $result;
    }


    public function detail($param)
    {
        $order_id = $param['sn2'];
        $order = new TborderDao();
        $item = new ItemDao();
        $item_img = new ItemImgDao();
        $itemtuan = new ItemTuanDao();
        $record = new PayRecordDao();
        $tt_order = new Order();
        $att = new attachmentDao();
        $result['order'] = $order->get_sn2($order_id);
        $result['record'] = $record->get_by_payno($result['order']['pay_no']);
        if($result['order']['delivery_date2'] == true){
            $date_times = strtotime($result['order']['delivery_date2']);
            $week = $tt_order->weekday(date("w",$date_times));
            $result['order']['delivery_date'] = date('Y年n月j日',$date_times)."周($week)";
        }
        $result['item'] = $item->get_bt_item_id($result['order']['item_id']);
        if($result['order']['money'] < self::FREEFREIGHT && $result['order']['status'] == 0 && $result['item']['tuan_num'] <= 1){
            $result['order']['freight'] = self::FREIGHT;
            $result['order']['count_money'] = $result['order']['money']+self::FREIGHT;
        }else{
            $result['order']['count_money'] = $result['order']['money']+$result['record']['freight'];
        }
        $result['item_img'] = $item_img->get_item_cover($result['item']['product_id']);
        $result['item']['img_url'] = $att->getUrlAttr($result['item_img']['img_id']);
        $result['tuan'] = $itemtuan->get_payid($result['order']['pay_no']);
        if ($result['tuan'] == true) {
            $result['tuan']['pay_nm'] = $itemtuan->get_create_count($result['tuan']['create_uid']);
        }
        return $result;
    }

    public function cnacelBySn2($sn2){
        $tborderDao = new TborderDao();
        $order = $tborderDao->get_sn2($sn2);
        if($order['status']!= 0){
            return false;
        }
        return $tborderDao->set_sn2($sn2,-1);
    }

    public function set_order($payid)
    {
        $pay_record = new PayRecordDao();
        $order = new TborderDao();
        $item = new ItemDao();

        $ItemSaleRecordDao = new ItemSaleRecordDao();

        $order_status = new OrderStatusDao();
        $order->set_status($payid, 1);
        $pay_record->set_status($payid, 2);
        $order_data = $order->get_by_payno($payid);

        foreach ($order_data as $key => $val) {
            $item_data = $item->get_bt_item_id($val['item_id']);
            $data_sale_record = array(
                'item_id' => $item_data['id'],
                'title' => $item_data['title'],
                'price_single' => $item_data['price_single'],
                'price_original' => $item_data['price_original'],
                'sale_num' => $val['item_num'],
                'create_date'=>date('Y-m-d H:i:s'),
            );

             $ItemSaleRecordDao->insert($data_sale_record);

            if($item_data['pac_num'] > 1){
                $itembox = new ItemBoxDao();
                $box_data = $itembox->get_health_box($item_data['id']);
                foreach($box_data as $k=>$v){
                    $item->set_inventory($v['item_id'],$val['item_num']);
                }
            }else{
                $item->set_inventory($val['item_id'],$val['item_num']);
            }
        }


        $order_box = $order->get_by_payno_all($payid);

        foreach($order_box as $k1=>$v1){
            $statud_data = array(
                'sn2'=>$v1['sn2'],
                'status'=>1,
                'remark'=>'备货中',
                'update_date'=>date('Y-m-d H:i:s'),
                'order_id'=>$v1['id']
            );
            $order_status->insert($statud_data);
            $order->set_update($v1['id'],array('create_date'=>date('Y-m-d H:i:s')));
        }

        $url = url('member/payment/finish', array("payid" => $payid));
        $array = array("msg" => "success", "code" => 3, "data" => ['url'=>$url]);
        return $array;
    }


    /** 修改团购订单状态 **/
    public function set_tuan_order($payid)
    {
        $pay_record = new PayRecordDao();
        $order = new TborderDao();
        $order_status = new OrderStatusDao();
        $item = new ItemDao();
        $itemtuan = new ItemTuanDao();
        // new ItemSaleRecordDao
        $ItemSaleRecordDao=new ItemSaleRecordDao();

        $order->set_status($payid, 1);
        $pay_record->set_status($payid, 2);
        $order_data = $order->get_by_payno_one($payid);
        //商品信息在这里
        $item_data = $item->get_bt_item_id($order_data['item_id']);
        $tuan_info = $itemtuan->get_payid($payid);

        if ($tuan_info['behavior'] == 1) {

            $tuan_data['status'] = 2;
            $itemtuan->set_status($payid, $tuan_data);
        } else {
            $tuan_data['status'] = 2;
            $itemtuan->set_status($payid, $tuan_data);
            //开团成功之后清除所有未支付团商品的订单
            $user_notpay = $itemtuan->get_item_create_behavior($order_data['item_id'], $tuan_info['create_uid'], 1, $tuan_info['behavior']);
            foreach ($user_notpay as $key => $val) {
                $order->set_del_flag($val['pay_no'], 1,0);
            }
        }
        $item->set_inventory($order_data['item_id'], $order_data['item_num']);

        $tuan_count = $itemtuan->get_create_count($tuan_info['create_uid']);
        if ($tuan_count >= $item_data['tuan_num']) {
            $delivery_date = date("Y-m-d", strtotime("+1 day")); //全部支付成功之后给予所有参团订单时间
            $tuan_data_all = $itemtuan->get_create_all($tuan_info['create_uid']);
            //满团之后清除所有未支付订单
            foreach ($tuan_data_all as $k => $v) {
                if ($v['status'] == 1) {
                    $order->set_del_flag($v['pay_no'], 1);
                } elseif ($v['status'] == 2) {
                    //每一条的订单信息
                    $order_detail = $order->get_by_payno_one($v['pay_no']);
                    $order->set_data($order_detail['id'],array('create_date'=>date('Y-m-d H:i:s')));
                    $statud_data = array(
                        'sn2'=>$order_detail['sn2'],
                        'status'=>1,
                        'remark'=>'备货中',
                        'update_date'=>date('Y-m-d H:i:s'),
                        'order_id'=>$order_detail['id']
                    );
                    $order_status->insert($statud_data);
                    $order->set_delivery_date($v['pay_no'], $delivery_date);

                    //  make up ItemSaleRecord  add_data  这里在做吧.
                    $data_sale_record = array(
                        'item_id' => $item_data['id'],
                        'title' => $item_data['title'],
                        'price_single' => $item_data['price_single'],
                        'price_original' => $item_data['price_original'],
                        'sale_num' => $order_detail['item_num'],
                        'create_date'=>date('Y-m-d H:i:s'),
                    );

                    $ItemSaleRecordDao->insert($data_sale_record);

                }
            }

        }

        $url = url('item/tuan/join', array("payid" => $payid));
        $array = array("msg" => "success", "code" => 3, "data" => ['url'=>$url]);
        return $array;
    }



    public function get_pay_list($param)
    {
        $Item = new ItemDao();
        $order = new TborderDao();
        $order_model = new Order();
        $pay_data = $order->get_by_payno_all($param['pay_id']);
        $item_img = new ItemImgDao();
        $list = [];
        $att = new attachmentDao();
        foreach ($pay_data as $key => $val) {
            $list[$key] = $Item->get_bt_item_id($val['item_id']);
            $list[$key]['order'] = $pay_data[$key];

            $list[$key]['img'] = $item_img->get_item_cover($list[$key]['product_id']);
            $list[$key]['img_url'] = $att->getUrlAttr($list[$key]['img']['img_id']);
            $date_times = strtotime($list[$key]['order']['delivery_date2']);
            $week = $order_model->weekday(date("w", $date_times));
            $list[$key]['order']['delivery_date'] = date('Y年n月j日', $date_times) . "周($week)";
        }

        return $list;
    }

    public function receive($param){
        $tb_order = new TborderDao();
        $order_status = new OrderStatusDao();
        $order_info = $tb_order->get_by_payno_all($param['order_id']);
        $result = '';
        foreach($order_info as $k => $v){
            $data = [
                'sn2' => $v['sn2'],
                'status' => $param['order_status'],
                'remark' => $param['order_status_name'],
                'update_date' => date('Y-m-d H:i:s'),
                'order_id' => $v['id']
            ];
            if($param['order_status'] == 3){
                $tb_order->set_data($v['id'],2);
            }
            $result = $order_status->insert($data);
        }
        if($result){
            return ['status'=>200];
        }
    }

    //退款
    public function refund($sn2,$userid){
        $tborderDao = new TborderDao();
        $order = $tborderDao->get_sn2($sn2);
        if($order && ($order['status'] == 1 || $order['status'] == 2)&&$order['user_id'] ==$userid){
            return $tborderDao->set_sn2($sn2,4);
        }else{
            return false;
        }

    }

}