<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/22 0022
 * Time: 上午 11:52
 */

namespace gyo2o\model;


use gyo2o\dao\attachmentDao;
use gyo2o\dao\OrderStatusDao;
use gyo2o\dao\TborderDao;

class OrderStatus
{

    public function setOrderStatus($orderid,$mark){
        $orderStatusDao = new OrderStatusDao();
        if($orderStatusDao->getByOrderidAndRemark($orderid,$mark)){
            return true;
        }

        $orderDao = new TborderDao();
        $order = $orderDao->getByOrderId($orderid);

        $data = ['order_id'=>$orderid,'update_date'=>date('Y-m-d H:i:s'),'remark'=>$mark,'sn2'=>$order['sn2']];
        if($mark=='配送成功'){
            $data['status'] = 2;
        }else{
            $data['status'] = 1;
        }

        return $orderStatusDao->save($data);

    }

    public function getOrderStatusList($id){
        $orderStatusDao = new OrderStatusDao();
        $list = $orderStatusDao->getById($id);
        if($list){
            return ['total'=>count($list),'rows'=>$list];
        }
        return ['total'=>0,'rows'=>[]];
    }
}