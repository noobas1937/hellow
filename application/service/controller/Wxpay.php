<?php
namespace app\service\controller;


use gyo2o\dao\ItemDao;
use gyo2o\dao\PayRecordDao;
use gyo2o\dao\TborderDao;
use gyo2o\model\Tborder;
use gyo2o\wechat\WxPayNotify;
use think\Controller;

class Wxpay extends Controller
{
    public function notify(){
        $payid = input('post.payid');
        if($payid){
            $order_pay = new PayRecordDao();
            $order_status = $order_pay->get_by_payno($payid);
            $order = new Tborder();

            if($order_status['status'] == 1){
                $result = $order->set_order($payid);
                $tb_order_dao = new TborderDao();
                $order_info = $tb_order_dao->get_by_payno_all($payid);
                $this->order_back($order_info);
            }else{
                $url = url('member/payment/finish', array("payid" => $payid));
                $result = array("msg" => "success", "code" => 3, 'data'=> ["url" => $url]);
            }
            return json($result);
        }else{
            $raw_xml = file_get_contents("php://input");
            $wxpaynotify = json_decode(json_encode(simplexml_load_string($raw_xml,'SimpleXMLElement', LIBXML_NOCDATA)),true);
            if($wxpaynotify['result_code'] == 'SUCCESS'){
                 $order_pay = new PayRecordDao();
                 $order_status = $order_pay->get_by_payno($wxpaynotify['out_trade_no']);
                 $wxnotify = new WxPayNotify();
                 if($order_status['status'] == 1){
                     $order = new Tborder();
                     $result = $order->set_order($wxpaynotify['out_trade_no']);
                     if($result['msg'] == "success"){
                         $tb_order_dao = new TborderDao();
                         $order_info = $tb_order_dao->get_by_payno_all($wxpaynotify['out_trade_no']);
                         $this->order_back($order_info);
                        $wxnotify->Handle(false);
                     }
                 }else{
                     $wxnotify->Handle(false);                 
                 }
            }
            die();
        }
    }

    public function order_back($order_info){
        $item = new ItemDao();
        $data = [
            'user_type' => 1,
            'create_id' => 1044,
            'source' => 'ttgy',
        ];
        $task_info = '';
        foreach($order_info as $k => $v){
            $item_info = $item->get_bt_item_id($v['item_id']);
            $data['receipt_address'] = $v['address'];
            $data['receipt_address_detail'] = $v['address'];
            $data['receipt_contact_mobile'] = $v['mobile'];
            $data['receipt_name'] = $v['name'];
            $data['out_trade_num'] = $v['pay_no'];
            $data['receipt_remark'] = $v['memo'];
            $task_info .= $item_info['title'].'×'.$v['item_num'];
            $data['task_info'] = $task_info;
        }
        $result = $this->post('https://open.connect-city.cn/merchant/api/addTask',$data);
//        $result = $this->post('http://zhh.www.fastadmin.com/merchant/api/addTask',$data);

    }

    public function notify_tuan(){
        $payid = input('post.payid');
        if($payid){
            $order_pay = new PayRecordDao();
            $order_status = $order_pay->get_by_payno($payid);
            if($order_status['status'] == 1){
                $order = new Tborder();
                $result = $order->set_tuan_order($payid);
                $tb_order_dao = new TborderDao();
                $order_info = $tb_order_dao->get_by_payno_all($payid);
                $this->order_back($order_info);
                return json($result);
            }else{
                $url = url('item/tuan/join', array("payid" => $payid));
                $array = array("msg" => "success", "code" => 3, 'data'=> ["url" => $url]);
                return json($array);
            }
        }else{
            $raw_xml = file_get_contents("php://input");
            $wxpaynotify = json_decode(json_encode(simplexml_load_string($raw_xml,'SimpleXMLElement', LIBXML_NOCDATA)),true);
            if($wxpaynotify['result_code'] == 'SUCCESS'){
                 $order_pay = new PayRecordDao();
                 $order_status = $order_pay->get_by_payno($wxpaynotify['out_trade_no']);
                 $wxnotify = new WxPayNotify();
                 if($order_status['status'] == 1){
                     $order = new Tborder();
                     $result = $order->set_tuan_order($wxpaynotify['out_trade_no']);
                     if($result['msg'] == "success"){
                         $tb_order_dao = new TborderDao();
                         $order_info = $tb_order_dao->get_by_payno_all($wxpaynotify['out_trade_no']);
                         $this->order_back($order_info);
                        $wxnotify->Handle(false);
                     }
                 }else{
                     $wxnotify->Handle(false);                 
                 }
            }
            die();
        }
    }

    public function post($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        $response = json_decode($response,true);
        if(!is_array($response)){
            $response = json_decode($response,true);
        }
        $response['httpCode'] = $httpCode;
        curl_close($ch);
        return $response;
    }

    /**
     * 地址解析
     * @param $address
     * @return array
     */
    public  function address_parser($address){
        //$data = ['province'=>0,'city'=>0,'area'=>0,'streets'=>'','root'=>'','jl'=>''];
        $data = [];
        $length = stripos($address,'省');
        if($length !== false){
            $data['province'] = substr($address,0,$length+3);
            $address = str_replace($data['province'],'',$address);
        }

        $length = stripos($address,'市');
        if($length !== false) {
            $data['city'] = substr($address, 0, $length + 3);
            $address = str_replace($data['city'], '', $address);
        }

        $length = stripos($address,'区');
        if($length !== false) {
            $data['area'] = substr($address, 0, $length + 3);
            $address = str_replace($data['area'], '', $address);
        }


        //处理距离
        $ji2 = mb_substr($address,-2);
        if('公里' == $ji2 || '千米' == $ji2 ){
            $address = str_replace($ji2,'km',$address);
        }

        $ji1 = mb_substr($address,-1);
        if('米' == $ji2 ){
            $address = str_replace($ji1,'m',$address);
        }

        $pattern = '/([0-9]+)([.|,]+)([0-9]+)([km|m]*)$/u';
        preg_match($pattern,$address,$matches);

        if(0 < sizeof($matches) && 0 < strlen($matches[0])){
            $data['jl'] = $matches[0];
            $address = str_replace($data['jl'],'',$address);
        }

        // 34-23-44-45
        $pattern = '/([0-9]+)-([0-9]+)(-*)([0-9]*)(-*)([0-9]*)$/u';
        preg_match($pattern,$address,$matches);
        if(0 < sizeof($matches) && 0 < strlen($matches[0])){
            $data['room'] = $matches[0];
            $data['streets'] = str_replace($data['room'],'',$address);
            return $data;
        }

        //(全州国际三路)
        $key = '(';
        if(strstr($address,$key)){
            $length = strripos($address,$key);
            $_lastin = mb_substr($address,0-$length);
            if(strstr($_lastin,'楼') || strstr($_lastin,'栋') || strstr($_lastin,'单元')){
                $data['streets'] = substr($address,0,$length);
                $data['room'] = str_replace($data['streets'],'',$address);
                $data['room'] = ltrim($data['room'],'(');
                $data['room'] = rtrim($data['room'],')');
                return $data;
            }
        }

        // 34_23_44_45
        $pattern = '/([0-9]+)_([0-9]+)(_*)([0-9]*)(_*)([0-9]*)$/u';
        preg_match($pattern,$address,$matches);
        if(0 < sizeof($matches) && 0 < strlen($matches[0])){
            $data['room'] = $matches[0];
            $data['streets'] = str_replace($data['room'],'',$address);
            return $data;
        }

        //xx栋xx号
        //xx栋xxx房
        //xx栋xx楼xx单元
        $key = '栋';
        if(strstr($address,$key)){
            $length = strripos($address,$key);
            $data['streets'] = substr($address,0,$length).$key;
            $data['room'] = str_replace($data['streets'],'',$address);
            return $data;
        }

        //xx单元
        $key = '单元';
        if(strstr($address,$key)){
            $length = strripos($address,$key);
            $data['streets'] = substr($address,0,$length).$key;
            $data['room'] = str_replace($data['streets'],'',$address);
            return $data;
        }

        $key = '室';
        if(strstr($address,$key)){
            $length = strripos($address,$key);
            $data['streets'] = substr($address,0,$length).$key;
            $data['room'] = str_replace($data['streets'],'',$address);
            return $data;
        }

        $data['streets'] = $address;
        return $data;
    }


}
