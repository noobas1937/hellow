<?php
namespace app\order\controller;

use app\common\controller\Api;
use gyo2o\model\Order;
use gyo2o\model\Record;
use gyo2o\model\Tborder;
use gyo2o\model\Evals;


class Ajax extends Api
{
    public function order_confirm()
    {
        $param = input('post.');
        $validate = $this->validate($param,'order/OrderValidate.order_confirm');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new Record();
            $result = $class->order_confirm($param);
            $result = $this->return_dao($result,'生成成功','失败');
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function add_order(){
        $param = input('post.');
        $validate = $this->validate($param,'order/OrderValidate.add_order');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new Order();
            $result = $class->generate($param);
            $result = $this->return_dao($result,'生成成功','失败');
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function confirm_data(){
        $param = input('post.');
        $validate = $this->validate($param,'order/OrderValidate.confirm_data');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new Order();
            $result = $class->confirm_data($param);
            $result = $this->return_dao($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function order_list(){
        $param = input('post.');
        $validate = $this->validate($param,'order/OrderValidate.order_confirm');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new Tborder();
            $result = $class->get_list($param);
            $result = $this->return_list($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function order_pay(){
        $param = input('post.');
        $validate = $this->validate($param,'order/OrderValidate.order_confirm');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new Tborder();
            $result = $class->order_pay($param);
            $result = $this->return_dao($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function get_pay_list(){
        $param = input('post.');
        $validate = $this->validate($param,'order/OrderValidate.order_confirm');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new Tborder();
            $result = $class->get_pay_list($param);
            $result = $this->return_dao($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function get_status_list(){
        $param = input('post.');
        $validate = $this->validate($param,'order/OrderValidate.get_status_list');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new Tborder();
            $result = $class->get_order_status($param);
            $result = $this->return_list($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function get_detail(){
        $param = input('post.');
        $validate = $this->validate($param,'order/OrderValidate.get_detail');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new Tborder();
            $result = $class->detail($param);
            $result = $this->return_dao($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function comment()
    {
        $Eval = new Evals();

        if ('add' == $this->request->post('method')) {
            //todo add comments to item
            $post = $this->request->post();
            $item_id = $this->request->post('item_id');
            $sn2 = $this->request->post('sn2');
            if(empty($sn2)||empty($item_id)||empty($post['content'])||empty($post['user_id'])){
                return json([["status" => "failer", "code" => 4, "msg" => "缺少参数"]]);
            }

            if (1 == $this->request->post('tt_date')) {
                $create_date = $this->request->post('create_date');
            } else {
                $create_date = false;
            }

           $result = $Eval->add_eval($post, $item_id, $sn2, $create_date);
        } elseif ('replay' == $this->request->post('method')) {
            $eval_id = $this->request->post('eval_id');
            $comment = $this->request->post('comment');
            if(empty($eval_id)||empty($comment)){
                return json([["status" => "failer", "code" => 4, "msg" => "缺少参数"]]);
            }
            $result = $Eval->reply($eval_id, $comment);
        }
        return json($result);
    }

    public function cnacle()
    {
        $uid = $this->request->post('user_id');
        $sn2 = $this->request->post('sn2');
        if(empty($uid) || empty($sn2)){
            return json([["status" => "failer", "code" => 4, "msg" => "缺少参数"]]);
        }

        $Order = new Tborder();
        if($Order->cnacelBySn2($sn2)!==false){
            return json([["status" => "success", "code" => 3, "msg" => "取消订单成功"]]);
        }else{
            return json([["status" => "failer", "code" => 4, "msg" => "取消订单失败"]]);
        }
    }

    public function refund(){
        $userid = $this->request->post('user_id');
        $sn2 = $this->request->post('sn2');
        if(empty($sn2)||empty($userid)){
            return json([["status" => "failer", "code" => 4, "msg" => "缺少参数"]]);
        }
        $orderDao = new Tborder();
        if($orderDao->refund($sn2,$userid)!==false){
            return json([["status" => "success", "code" => 3, "msg" => "退款成功"]]);
        }else{
            return json([["status" => "failer", "code" => 4, "msg" => "退款失败"]]);
        }
    }


}
