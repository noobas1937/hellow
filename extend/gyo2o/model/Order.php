<?php

namespace gyo2o\model;

use EasyWeChat\Core\Exception;
use gyo2o\BaseModel;
use gyo2o\dao\AccountDao;
use gyo2o\dao\attachmentDao;
use gyo2o\dao\CartDao;
use gyo2o\dao\DataDictionaryDao;
use gyo2o\dao\DishesDao;
use gyo2o\dao\ItemDao;
use gyo2o\dao\ItemImgDao;
use gyo2o\dao\ItemTuanDao;
use gyo2o\dao\OrderDao;
use gyo2o\dao\PackagedetailDao;
use gyo2o\dao\RiderDao;
use gyo2o\dao\TborderDao;
use gyo2o\dao\UserAddressDao;
use gyo2o\dao\UserStatusDao;
use gyo2o\wechat\JsApiPay;
use gyo2o\wechat\Wechat;
use gyo2o\wechat\WxPayApi;
use gyo2o\wechat\WxPayUnifiedOrder;
use think\Url;
use Wechat\Loader;
use Wechat\WechatPay;


class Order extends BaseModel
{

    /**
     * 处理下单之前的数据
     * @param $param
     * @return mixed
     */
    public function confirm_data($param)
    {
        $item = new ItemDao();
        $address = new UserAddressDao();
        $item_img = new ItemImgDao();
        $dictionary = new DataDictionaryDao();

        $data['cart'] = json_decode($param['cart'],true);
        $user_id = session('user_id') ? session('user_id') : $param['user_id'];
        if (!empty($data['item_id'])) {
            $result['item_id'] = $data['item_id'];
            $result['item'] = $item->get_bt_item_id($data['item_id']);
            $result["item"]['img'] = $item_img->get_item_cover($result['item']['product_id']);
            $result['cart']['priceSum'] = $result['item']['price_single'];
            $result['is_package'] = 2;
        }
        // 购物车商品
        if (!empty($data['cart'])) {
            $copies = 0;
            $pac_num = 0;
            $att = new attachmentDao();
            foreach ($data['cart']['shopping'] as $key => $val) {
                $result["cart"]['shopping'][$key] = $item->get_bt_item_id($val['item_id']);
                $result["cart"]['shopping'][$key]['img'] = $item_img->get_item_cover($result["cart"]['shopping'][$key]['product_id']);
                $result["cart"]['shopping'][$key]['img_url'] = $att->getUrlAttr($result["cart"]['shopping'][$key]['img']['img_id']);
                $result["cart"]['shopping'][$key]['number'] = $val['number'];
                $copies += $data["cart"]['shopping'][$key]['number'];
                $pac_num += $result["cart"]['shopping'][$key]['pac_num'];
            }
            $result['cart']['priceSum'] = $data['cart']['priceSum'];
            $result['cart']['num_count'] = $copies;
            if ($pac_num > count($result["cart"]['shopping'])) {
                $result['is_package'] = 1;
                $result['time_arr'] = $this->package_data_time();
            } else {
                $result['tom_time'] = date("Y年n月j日", strtotime("+1 day"));
                $result['week'] = $this->weekday(date("w", strtotime("+1 day")));
                $result['is_package'] = 2;
            }
        }
        //地址1
        if (!empty($data['address_id1'])) {
            $result['address1'] = $address->get_by_id($data['address_id1']);
        } else {
            $result['address1'] = $address->get_by_default($user_id);
        }
        if (true == $result['address1']) {
            $city = $dictionary->get_by_id($result['address1']['city']);
            if ($city) {
                $result['address1']['city'] = $city['name'];
            }
            $area = $dictionary->get_by_id($result['address1']['area']);
            if ($area) {
                $result['address1']['area'] = $area['name'];
            }
        }
        if (isset($data['address_id2']) && !empty($data['address_id2'])) {
            $result['address2'] = $address->get_by_id($data['address_id2']);
        }
        if (isset($data['address_id2']) && true == $result['address2']) {
            $city = $dictionary->get_by_id($result['address2']['city']);
            if ($city) {
                $result['address2']['city'] = $city['name'];
            }
            $area = $dictionary->get_by_id($result['address2']['area']);
            if ($area) {
                $result['address2']['area'] = $area['name'];
            }
        }
        //配送时间1
        if (!empty($data['time_slot1'])) {
            $result['time_slot1'] = $data['time_slot1'];
        }
        if (!empty($data['time_slot2'])) {
            $result['time_slot2'] = $data['time_slot2'];
        }
        if (!empty($data['date1']) || !empty($data['date2'])) {
            $post['date1'] = $data['date1'];
            $post['date2'] = $data['date2'];
            $result['data_arr'] = $this->choice_date($post);
            $result['date_time_arr'] = $post['date1'] . $post['date2'];
        }
        return $result;
    }

    public function choice_date($post)
    {
        $date1 = null;
        $date2 = null;
        if ($post['date1'] == true) {
            $date1 = explode(",", $post['date1']);
        }
        if ($post['date2'] == true) {
            $date2 = explode(",", $post['date2']);
        }
        $data_arr['number'] = count($date1) + count($date2);
        for ($i = 0; $i < $data_arr['number']; $i++) {
            if ($i < count($date1)) {
                $data_arr[$i]['date_time'] = $date1[$i];
                $data_arr[$i]['type'] = 1;
            } else {
                $data_arr[$i]['date_time'] = $date2[$i - count($date1)];
                $data_arr[$i]['type'] = 2;
            }
        }
        $data_arr['date1'] = $post['date1'];
        $data_arr['date2'] = $post['date2'];
        return $data_arr;
    }

    public function weekday($time)
    {
        $week_array = array(
            "日",
            "一",
            "二",
            "三",
            "四",
            "五",
            "六"
        );
        $week = $week_array[$time];
        return $week;
    }

    public function package_data_time()
    {
        $result = [];
        for ($i = 0; $i < 11; $i++) {
            $result[$i]['month'] = date("n-j", time() + $i * 86400 + 86400);
            $result[$i]['week'] = $this->weekday(date('w', time() + $i * 86400 + 86400));
            $result[$i]['times'] = time() + $i * 86400 + 86400;
            $result[$i]['date_time'] = date("Y-m-d",time() + $i * 86400 + 86400);
        }
        return $result;
    }

    /**
     * @param $post
     * @return array
     */
    public function generate($post)
    {
        $city_id = $this->city_id;
        $address1 = null;
        $date_name1 = null;
        $date_mobile1 = null;
        $address2 = null;
        $date_name2 = null;
        $date_mobile2 = null;
        $item_data = [];
        $behavior = null;

        $item = new ItemDao();
        $cart = new CartDao();
        $address = new UserAddressDao();
        $order = new TborderDao();
        $dictionary = new DataDictionaryDao();
        $pay_id = isset($post['pay_id']) ? $post['pay_id'] : null;
        $pay_data = session($pay_id) ? json_decode(session($pay_id), true) : $post;
        $pay_data['cart'] = json_decode($pay_data['cart'],true);
        $user_info_status = new UserStatusDao();
        $user_id = session('userid') ? session('userid') : $post['user_id'];
        $user_status = $user_info_status->get_by_userid($user_id);
        $itemtuan = new ItemTuanDao();
        if (isset($pay_data['item_id']) && $pay_data['item_id']) {
            $item_data = $item->get_bt_item_id($pay_data['item_id']);
            if($item_data['city_id'] != $user_status['city_id']){
                return $item_data['title'].'暂时缺货';
            }

            $behavior = $itemtuan->get_behavior($pay_data['creat_uid']);
            if ($behavior['end_time'] < date("Y-m-d H:i:s") && $behavior == true) {
                return '团购已过期';
            }
            $tuan_count = $itemtuan->get_tuan_count($pay_data['creat_uid']);
            $pay_success = $itemtuan->get_create_count($pay_data['creat_uid']);
            if ($tuan_count+$pay_success >= $item_data['tuan_num']) {
                return '已满团';
            }

        }else{
            foreach ($pay_data['cart']['shopping'] as $k => $v) {
                $item_data = $item->get_bt_item_id($v['item_id']);
                if($item_data['city_id'] != $user_status['city_id']){
                    return $item_data['title'].'暂时缺货';
                }
            }
        }
        //基本数据
        $data['pay_no'] = $pay_id;
        $data['user_id'] = $user_id;
        $data['create_date'] = date("Y-m-d H:i:s");
        $data['memo'] = $post['memo'];

        //单品购买时地址时间
        if ($pay_data['address_id1'] == '') {
            $address_data = $address->get_by_default($user_id);
            if (empty($address_data)) {
                return '请选择收货地址';
            }
        } else {
            $address_data = $address->get_by_id($pay_data['address_id1']);
        }
        if($address_data['city'] != $city_id){
            return '地址选择错误';
        }

        if ($address_data == true) {
            $city = $dictionary->get_by_id($address_data['city']);
            $area = $dictionary->get_by_id($address_data['area']);
            $date_name1 = $address_data['name'];
            $date_mobile1 = $address_data['mobile'];
            $address1 = $city['name'] . $area['name'] . $address_data['address'];
        }

        if ($pay_data['time_slot1'] == '') {
            $time_slot1 = $post['delivery'];
        } else {
            $time_slot1 = $pay_data['time_slot1'];
        }

        //套餐时间地址处理
        $time_arr = array();
        if ($post['is_package'] == 1) {
            if ($pay_data['time_slot2'] == '' && $post['delivery2'] != '') {
                $time_slot2 = $post['delivery2'];
            } else {
                $time_slot2 = $pay_data['time_slot2'];
            }
            if ($pay_data['address_id1'] == '') {
                $address_data2 = $address->get_by_default($user_id);
                if (empty($address_data2)) {
                    return '请选择收货地址';
                }
            } else {
                $address_data2 = $address->get_by_id($pay_data['address_id1']);
            }
            if($address_data2['city'] != $city_id){
                return '地址选择错误';
            }
            if ($address_data2 == true) {
                $city2 = $dictionary->get_by_id($address_data2['city']);
                $area2 = $dictionary->get_by_id($address_data2['area']);
                $date_name2 = $address_data2['name'];
                $date_mobile2 = $address_data2['mobile'];
                $address2 = $city2['name'] . $area2['name'] . $address_data2['address'];
            }

            $result1 = array();
            if ($pay_data['date1'] == true) {
                $time1 = explode(",", $pay_data['date1']);
                foreach ($time1 as $key => $val) {
                    $result1[$key]['delivery'] = $time_slot1;
                    $result1[$key]['time'] = $val;
                    $result1[$key]['address'] = $address1;
                    $result1[$key]['name'] = $date_name1;
                    $result1[$key]['mobile'] = $date_mobile1;
                }
            }
            $result2 = array();
            if ($pay_data['date2'] == true) {
                $time2 = explode(",", $pay_data['date2']);
                foreach ($time2 as $key1 => $val1) {
                    $result2[$key1]['delivery'] = $time_slot2;
                    $result2[$key1]['time'] = $val1;
                    $result2[$key1]['address'] = $address2;
                    $result2[$key1]['name'] = $date_name2;
                    $result2[$key1]['mobile'] = $date_mobile2;
                }
            }
            $time_arr = array_merge($result1, $result2);
            sort($time_arr);
        }

        //根据支付流水查看订单是否存在
        $order_data = $order->get_by_payno($pay_id);
        $item_id = isset($pay_data['item_id']) ? $pay_data['item_id'] : false;
        if ($order_data) {

            foreach ($order_data as $k => $v) {
                $item_data = $item->get_bt_item_id($v['item_id']);
                $orderid_all= $order->get_order_no_all($v['sn1']);

                if ($item_data['pac_num'] > 1) {
                    foreach ($orderid_all as $k1=>$v1){
                        $data['name'] = $time_arr[$k1]['name'];
                        $data['mobile'] = $time_arr[$k1]['mobile'];
                        $data['address'] = $time_arr[$k1]['address'];
                        $data['delivery_date2'] = $time_arr[$k1]['time'];
                        $data['delivery'] = $time_arr[$k1]['delivery'];
                        $order->set_update($v1['id'], $data);
                    }
                } elseif ($item_data['pac_num'] == 1) {
                    $data['name'] = $date_name1;
                    $data['mobile'] = $date_mobile1;
                    $data['delivery_date2'] = date("Y-m-d", strtotime("+1 day"));
                    $data['delivery'] = $time_slot1;
                    $data['address'] = $address1;
                    $order->set_update($v['id'], $data);
                }


            }
        } else {

            if ($item_id == '') {
                //购物车数据

                foreach ($pay_data['cart']['shopping'] as $key => $val) {
                    $add_result = [];
                    $item_data = $item->get_bt_item_id($val['item_id']);
                    $data['sn1'] = getIdentifier();
                    for ($i = 0; $i < $item_data['pac_num']; $i++) {
                        if ($item_data['pac_num'] > 1) {
                            $data['sn2'] = getIdentifier();
                            $data['name'] = $time_arr[$i]['name'];
                            $data['mobile'] = $time_arr[$i]['mobile'];
                            $data['address'] = $time_arr[$i]['address'];
                            $data['delivery_date2'] = $time_arr[$i]['time'];
                            $data['delivery'] = $time_arr[$i]['delivery'];
                        } elseif ($item_data['pac_num'] == 1) {
                            $data['sn2'] = $data['sn1'];
                            $data['name'] = $date_name1;
                            $data['mobile'] = $date_mobile1;
//                            $data['delivery_date'] = date("Y年n月j日", strtotime("+1 day")) . "周(" . $this->weekday(date("w", strtotime("+1 day"))) . ")";
                            $data['delivery_date2'] = date("Y-m-d", strtotime("+1 day"));
                            $data['delivery'] = $time_slot1;
                            $data['address'] = $address1;
                        }

                        $data['item_id'] = $val['item_id'];
                        $data['item_num'] = $val['number'];
                        $data['money'] = $val['number'] * $val['price_single'];

                        $add_result = $order->insert($data);
                    }
                    if ($add_result) {
                        $cart->set_flag($val['id'], 1);
                    }
                }
            } else {
                //参团数据
                $data['sn1'] = getIdentifier();
                $data['sn2'] = $data['sn1'];
                $data['name'] = $date_name1;
                $data['mobile'] = $date_mobile1;
                $data['item_id'] = $item_id;
                $data['item_num'] = $item_data['pac_num'];
                $data['money'] = $item_data['price_single'];
                $data['delivery'] = $time_slot1;
                $data['address'] = $address1;
                $add_result = $order->insert($data);
                if($add_result){
                    //团购下单成功或增加一条团购信息
                    $tuan_data['tuan_uid'] = $user_id;
                    $tuan_data['pay_no'] = $pay_id;
                    $tuan_data['item_id'] = $item_id;
                    $tuan_data['start_time'] = date("Y-m-d H:i:s");
                    if ($pay_data['creat_uid'] == '') {
                        $tuan_data['create_uid'] = getIdentifier();
                        $tuan_data['end_time'] = date("Y-m-d H:i:s", time() + $item_data['tuan_expire']);
                        $tuan_data['behavior'] = 0;
                    } else {
                        $tuan_data['end_time'] = $behavior['end_time'];
                        $tuan_data['create_uid'] = $pay_data['creat_uid'];
                        $tuan_data['behavior'] = 1;
                    }
                    $itemtuan->insert($tuan_data);
                }
            }
        }


        if ($item_id == '') {
            $url = "/service/wxpay/notify";
        } else {
            $url = "/service/wxpay/notify_tuan";
        }

        $price = ($post['money']+$post['freight'])*100;
        $array = [];
        if(isset($post['client']) && $post['client'] == 'wx'){
            $array = $this->wx_pay($pay_id, $url,$price,$user_id);
        }elseif(isset($post['client']) && $post['client'] = 'alipay'){
            $array = $this->alipay($pay_id,$post['money']+$post['freight']);
        }

        return $array;
    }

    public function wx_pay($pay_id, $url,$price,$user_id)
    {
        $tools = new JsApiPay();
        $user_account = new AccountDao();
        $openid = $user_account->get_by_id($user_id);
        $openId = $openid['wx_openid'];

        $input = new WxPayUnifiedOrder();
        $input->SetBody("订单号");
        $input->SetOut_trade_no($pay_id);
        $input->SetTotal_fee($price);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetNotify_url("http://" . $_SERVER['HTTP_HOST'] . $url);
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = WxPayApi::unifiedOrder($input);
        try{
            $jsApiParameters = $tools->GetJsApiParameters($order);
        }catch(Exception $e){

            $jsApiParameters = array();
        }

        if($jsApiParameters){
            $array = array("status" => "success", "code" => 100000, "jsapi" => $jsApiParameters, "pay_id" => $pay_id, "url" => $url);
        }else{
            $array = '支付参数错误；调用失败';
        }
        return $array;
    }

    public function alipay($pay_id,$mony){
        \think\Loader::import('alipay.aop.AopClient');
        \think\Loader::import('alipay.aop.request.AlipayTradeAppPayRequest');
        $alipayConfig = \think\Config::get('alipay');
        $aop = new \AopClient();
        $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $aop->appId = $alipayConfig['APP_ID'];
        $aop->rsaPrivateKey = $alipayConfig['RSAPRIVATEKEY'];
        $aop->format = "json";
        $aop->charset = "UTF-8";
        $aop->signType = "RSA2";
        $aop->alipayrsaPublicKey = $alipayConfig['ALIPAYRSAPUBLICKEY'];
        //实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
        $request = new \AlipayTradeAppPayRequest();
        //SDK已经封装掉了公共参数，这里只需要传入业务参数
        $bizcontent = "{\"body\":\"我是测试数据\","
            . "\"subject\": \"App支付测试\","
            . "\"out_trade_no\": \"$pay_id\","
            . "\"timeout_express\": \"30m\","
            . "\"total_amount\": \"$mony\","
            . "\"product_code\":\"QUICK_MSECURITY_PAY\""
            . "}";
        $request->setNotifyUrl(url("/service/alipay/notify",$vars = '', $suffix = true, $domain = true));
        $request->setBizContent($bizcontent);

        //这里和普通的接口调用不同，使用的是sdkExecute
        $response = $aop->sdkExecute($request);

        //htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
        return $response;//就是orderString 可以直接给客户端请求，无需再做处理。
    }

    //按天订单统计
    public function orderlist($date,$sid){

        //按站点统计人数
        $time = strtotime($date)+24*3600;
        if(empty($sid)){
            $where = "jointime<=$time and (leavetime = 0 or leavetime>$time)";
        }else{
            $where = "jointime<=$time and (leavetime = 0 or leavetime>$time) and site_id = $sid";
        }
        $riderDao = new RiderDao();

        $rider = $riderDao->statisticBySite($where);

        //按站点统计订餐情况
        $orderDao = new OrderDao();

        $order = $orderDao->field('site_id,dishes_type,count(dishes_type) cnu,site.name')->join('rider','rider_id = rider.id')->join('site','rider.site_id=site.id')
            ->group('site_id,dishes_type')->where("date='$date'")->select();

        //  获取当天餐品名
        $packageDetailDao = new PackagedetailDao();
        $dishes = $packageDetailDao->getDishesByDate($date);

        if(empty($dishes)){
            $list = [];
        }else {
            $names = array();
            $dishesDao = new DishesDao();
            $names['A'] = $dishesDao->getDishesName($dishes[0]['dishes_id']);
            $names['B'] = $dishesDao->getDishesName($dishes[0]['dishes1_id']);
            $names['C'] = $dishesDao->getDishesName($dishes[0]['dishes2_id']);
            $price['A'] = dishesprice($dishes[0]['dishes_id']);
            $price['B'] = dishesprice($dishes[0]['dishes_id']);
            $price['C'] = dishesprice($dishes[0]['dishes_id']);

            //以站点ID为键整理数据
            $sorder = array();
            foreach ($order as $value){
                $sorder[$value['site_id']][] = $value;
            }
            //统计各站点各菜品下单数没有下单的默认选择餐品A
            $list = array();
            $totala = 0;
            $totalb = 0;
            $totalc = 0;
            $dtotal = 0;
            $totalamoney = 0;
            foreach ($rider as $key =>&$value){
                $siteid = $value['site_id'];
                if(isset($sorder[$siteid])){
                    //计算默认订餐人数
                    $count = 0;
                    $typea = 0;
                    $typez = 0;
                    $typeb = 0;
                    $typec = 0;
                    foreach ($sorder[$siteid] as $k=>$sv){
                        if($sv['dishes_type']=='A'){
                            $typea = $sv['cnu'];
                        }elseif ($sv['dishes_type'] == 'B'){
                            $typeb = $sv['cnu'];
                        }elseif ($sv['dishes_type'] == 'C'){
                            $typec = $sv['cnu'];
                        }elseif ($sv['dishes_type'] == "Z"){
                            $typez = $sv['cnu'];
                        }
                        $count += $sv['cnu'];
                    }
                    //默认点餐人数
                    $default = $value['pnu'] - $count;
                    //站点总点餐数
                    $dnu = $value['pnu'] - $typez;
                    //餐品A点餐人数
                    $anu = $typea + $default;
                    $bnu = $typeb;
                    $cnu = $typec;
                    $totala+=$anu;
                    $totalb+=$bnu;
                    $totalc+=$cnu;
                    //站点点餐总金额
                    $smoney =$anu*$price['A']+$bnu*$price['B']+$cnu*$price['C'];
                    $dtotal+=($anu+$bnu+$cnu);
                    //拼装站点点餐数据
                    $temp = ['id'=>$siteid.'-'.$date,'date'=>$date,'sname'=>$sorder[$siteid][0]['name'],'pnu'=>$value['pnu'],'dnu'=>$dnu,'smoney'=>$smoney,'dtotal' =>&$dtotal,'totala'=>&$totala,'totalb'=>&$totalb,'totalc'=>&$totalc,'totalmoney'=>&$totalamoney];
                    $list[] = array_merge($temp,['ctype'=>'餐品A','cname'=>$names['A'],'cnu'=>$anu,'price'=>$price['A'],'money'=>number_format($anu*$price['A'],2)]);
                    $list[] = array_merge($temp,['ctype'=>'餐品B','cname'=>$names['B'],'cnu'=>$bnu,'price'=>$price['A'],'money'=>number_format($bnu*$price['B'],2)]);
                    $list[] = array_merge($temp,['ctype'=>'餐品C','cname'=>$names['C'],'cnu'=>$cnu,'price'=>$price['A'],'money'=>number_format($cnu*$price['C'],2)]);
                }else{
                    //站点全部默认点餐
                    $sname = get_site_name($siteid);
                    $smoney = $value['pnu']*$price['A'];
                    $list[] = ['id'=>$siteid.'-'.$date,'date'=>$date,'sname'=>$sname,'pnu'=>$value['pnu'],'dnu'=>$value['pnu'],'smoney'=>$smoney,'ctype'=>'餐品A','cname'=>$names['A'],'cnu'=>$value['pnu'],'price'=>$price['A'],'money'=>number_format($value['pnu']*$price['A'],2),'dtotal' =>&$dtotal,'totala'=>&$totala,'totalb'=>&$totalb,'totalc'=>&$totalc,'totalmoney'=>&$totalamoney];
                    $totala += $value['pnu'];
                    $dtotal += $value['pnu'];
                }

            }

            $totalamoney = number_format($price['A']*$totala +$price['B']*$totalb + $price['C']*$totalc,2);
            $totala = $names['A'].' '.$totala.' 份合计：￥'.number_format($price['A']*$totala,2).'元';
            $totalb = $names['B'].' '.$totalb.' 份合计：￥'.number_format($price['B']*$totalb,2).'元';
            $totalc = $names['C'].' '.$totalc.' 份合计：￥'.number_format($price['C']*$totalc,2).'元';

        }

        return $list;
    }

    //站点订单统计
    public function siteOrder($date,$siteid,$dishesType = null){
        //  获取当天餐品
        $packagedetailDao = new PackagedetailDao();
        $dishes = $packagedetailDao->getDishesByDate($date);


        if(empty($dishes)){
            $rider = array();
        }else{
            $names = array();
            $dishesDao = new DishesDao();
            $names['A'] = $dishesDao->getDishesName($dishes[0]['dishes_id']);
            $names['B'] = $dishesDao->getDishesName($dishes[0]['dishes1_id']);
            $names['C'] = $dishesDao->getDishesName($dishes[0]['dishes2_id']);

            //获取站点骑手
            $time = strtotime($date)+24*3600;
            $riderDao = new RiderDao();
            $rider = $riderDao->getRiderBySite($siteid,$time);


            //获取站点订餐信息
            $orderDao = new OrderDao();
            $order = $orderDao->field('rider.name rname,dishes_type,rider.id rid,order_status status')->join('rider','rider.id = rider_id')->
            join('site','site.id=rider.site_id')->where("date='$date' and site_id=$siteid")->select();

            //整理order数据
            $rorder = array();
            foreach ($order as $value){
                $rorder[$value['rid']] = $value;
            }
            foreach ($rider as $k=>$val){
                if(isset($rorder[$val['id']])){
                    //有订餐信息
                    $rider[$k]['status'] = $rorder[$val['id']]['status'];
                    $rider[$k]['dishes_type'] = $rorder[$val['id']]['dishes_type'];
                    if($time<=time()){
                        $rider[$k]['status'] = '已领';
                    }
                    if($rorder[$val['id']]['dishes_type'] == 'Z'){
                        //取消
                        unset($rider[$k]);
                    }elseif ($rorder[$val['id']]['dishes_type'] == 'A'){
                        if(!empty($dishesType)&& $dishesType != 'A'){
                            unset($rider[$k]);
                            continue;
                        }
                        $rider[$k]['cname'] = $names['A'];
                    }elseif ($rorder[$val['id']]['dishes_type'] == 'B'){
                        if(!empty($dishesType)&& $dishesType != 'B'){
                            unset($rider[$k]);
                            continue;
                        }
                        $rider[$k]['cname'] = $names['B'];
                    }elseif ($rorder[$val['id']]['dishes_type'] == 'C'){
                        if(!empty($dishesType)&& $dishesType != 'C'){
                            unset($rider[$k]);
                            continue;
                        }
                        $rider[$k]['cname'] = $names['C'];
                    }
                }else{
                    //没有订餐信息默认餐品A
                    if(!empty($dishesType)&& $dishesType != 'A'){
                        unset($rider[$k]);
                        continue;
                    }
                    $rider[$k]['cname'] = $names['A'];
                    $rider[$k]['dishes_type'] = 'A';
                    $rider[$k]['status'] = '待领';
                    if($time<=time()){
                        $rider[$k]['status'] = '已领';
                    }
                }
            }
        }


        return array("total" => 4, "rows" => array_values($rider));
    }

    //本周站点点餐数据
    public function siteOrderWeek($siteid){
        $monday = date('Y-m-d',(time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));
        $data = array();
        $weekstr = ['周一','周二','周三','周四','周五','周六','周日'];
        for($i=0;$i<7;$i++){
            $fulldate = date('Y-m-d',strtotime($monday)+$i*24*3600);
            $day = date('m/d',strtotime($monday)+$i*24*3600);
            $data[] = array('date'=>$fulldate,'day'=>$day,'weekday'=>$weekstr[$i]);
        }
        foreach ($data as &$value){
            $siteorder = $this->siteOrder($value['date'],$siteid);
            $list = $siteorder['rows'];
            $total = 0;
            $received = 0;
            $unreccived = 0;
            $dishescount = array();
            foreach ($list as $val){
                if($val['status']=='已领'){
                    $received ++;
                }
                if($val['status']=='未领'){
                    $unreccived ++;
                }
                $total ++;
                if(isset($dishescount[$val['cname']])){
                    $dishescount[$val['cname']]+=1;
                }else{
                    $dishescount[$val['cname']] = 1;
                }

            }
            $value['list'] = $list;
            $value['total'] = $total;
            $value['unreceived'] = $unreccived;
            $value['received'] = $received;
            $tmp = array();
            foreach ($dishescount as $k=>$nu){
                $tmp[] = array('name'=>$k,'number'=>$nu);

            }
            $value['dishescount'] = $tmp;
        }

        return $data;
    }

    //骑手领餐
    public function receive($riderid){
        $orderDao = new OrderDao();
        //获取当天订餐信息
        $date = date('Y-m-d');
        $order = $orderDao->getOrderByDate($date,$riderid);
        if(empty($order)){
            //没有点餐信息默认点餐
            $data = array('date'=>$date,'dishes_type'=>'A','order_status'=>'已领','rider_id'=>$riderid);
            if($orderDao->insert($data)){
                return true;
            }
            return false;
        }

        $orderInfo = $order->toArray();

        if($orderInfo['dishes_type']=='Z'){
            //今天取消
            return array('status'=>false,'msg'=>'今天未订餐');
        }

        if($orderInfo['order_status'] == '已领'){
            return array('status'=>false,'msg'=>'已取餐，不可重复取餐');
        }
        $order->order_status = '已领';
        if($order->save()){
            return true;
        }
        return false;
    }

    public function isReserve($data,$riderid){
        $dates = array();
        foreach ($data as $value){
            $dates[] = $value['date'];
        }

        $dates = implode(',',$dates);
        $orderDao = new OrderDao();
        $result = $orderDao->getReceiveOrder($dates,$riderid);
        if($result){
            return true;
        }
        return false;
    }

    public function editOrder($riderid,$date,$dishesType){
        $orderDao = new OrderDao();
        return $orderDao->editOrder($riderid,$date,$dishesType);
    }

    public function getOrderInfo($riderid){

        $orderDao = new OrderDao();
        $orderInfo = $orderDao->getNextWeekOrder($riderid);
        if(empty($orderInfo)){
            return false;
        }
        $orderInfo = collection($orderInfo)->toArray();
        $temp = array();
        $packageDetaiDao = new PackagedetailDao();
        $type = ['A'=>'','B'=>'1','C'=>'2'];
        $weekstr = ['0'=>'周日','1'=>'周一','2'=>'周二','3'=>'周三','4'=>'周四','5'=>'周五','6'=>'周六'];
        foreach ($orderInfo as $value){
            $packageDetail = $packageDetaiDao->getDishesByDate($value['date']);
            $packageDetail = $packageDetail[0]->toArray();
            if($value['dishes_type'] != 'Z'){
                $dishesId = $packageDetail['dishes'.$type[$value['dishes_type']].'_id'];
                $dishesDao = new DishesDao();
                $dishe = $dishesDao->getDishesById($dishesId);
                $dishe = $dishe->toArray();
                $date = date('m/d',strtotime($value['date']));
                $date2 = date('m-d',strtotime($value['date']));
                $weekday = $weekstr[date('w',strtotime($value['date']))];
                $temp[] = array('date'=>$value['date'],'date2'=>$date2,'dateday'=>$date,'weekday'=>$weekday,'cname'=>$dishe['name'],'image'=>$dishe['dishes_image'],'price'=>$dishe['dishes_price']);
            }else{
                continue;
            }

        }

        return $temp;
    }

    public function riderDayOrder($riderid,$date){
        //判断是否在职
        $riderDao = new RiderDao();
        $rider = $riderDao->getRider($riderid);
        $jointime = $rider['jointime'];
        $leavetime = $rider['leavetime'];
        $time = strtotime($date) + 3600*24;
        if(!empty($jointime)){
            if($jointime>$time){
                //还未入职
                return false;
            }
        }
        if(!empty($leavetime)){
            if($leavetime < strtotime($date)){
                //已离职
                return false;
            }
        }

        //获取当天餐品
        $detail = new PackagedetailDao();
        $row = $detail->getDishesByDate($date);
        $row = collection($row)->toArray();
        if(empty($row)){
            return false;
        }
        $row = $row[0];
        $order = new OrderDao();
        $riderOrder = $order->getOrderByDate($date,$riderid);
        if(!empty($riderOrder)){
            //有订餐信息
            $type = $riderOrder['dishes_type'];
            $reult['status'] = $riderOrder['order_status'];
            if($time<=time()){
                $reult['status'] = '已领';
            }
            //判断是否可以修改
            $nex_monday = date('w')==1?strtotime('+2 monday',time()):strtotime('+1 monday',time());
            if(strtotime($date)>=$nex_monday && date('w')<=5){
                if(date('w')==5){
                    if(date('G')<=12){
                        $reult['editable'] = 1;
                    }else{
                        $reult['editable'] = 0;
                    }
                }else{
                    $reult['editable'] = 1;
                }

            }else{
                $reult['editable'] = 0;
            }

            if($type=='Z'){
                return false;
            }
        }else{
            $type = 'A';
            $reult['status'] = '待领';
            if($time<=time()){
                $reult['status'] = '已领';
            }
            $reult['editable'] = 0;
        }


        $temp = ['A'=>'','B'=>1,'C'=>2];
        $weekstr = ['0'=>'周日','1'=>'周一','2'=>'周二','3'=>'周三','4'=>'周四','5'=>'周五','6'=>'周六'];
        $reult['name'] = id2name('dishes',$row['dishes'.$temp[$type].'_id']);
        $reult['price'] = dishesprice($row['dishes'.$temp[$type].'_id']);
        $reult['image'] = dishesimage($row['dishes'.$temp[$type].'_id']);
        $reult['date'] = $date;
        $reult['date1'] = date('m-d',strtotime($date));
        $reult['weekday'] = date('w',strtotime($date));
        $reult['weekday'] = $weekstr[$reult['weekday']];


        return $reult;
    }

    public function siteCheck($siteid,$riderid){
        $riderDao = new RiderDao();
        $rider = $riderDao->getRider($riderid);
        if(empty($rider)){
            return true;
        }
        return $rider['site_id'] == $siteid;
    }

    public function cancel($riderid,$dates){
        $orderDao = new OrderDao();
        $orders = $orderDao->where(['date'=>['in',$dates],'rider_id'=>$riderid])->select();
        $orderDao->startTrans();
        $temp = [];
        foreach ($orders as $value){
            $value->dishes_type = 'Z';
            $temp[] = $value['date'];
            if($value['order_status']=='待领'){
                if($value->save()===false){
                    $orderDao->rollback();
                    return false;
                }
            }
        }
        $newInsert = array_diff($dates,$temp);
        if(!empty($newInsert)){

            //插入新记录
            $data = [];
            foreach ($newInsert as $key=>$value){
                $data[$key]['date'] = $value;
                $data[$key]['rider_id'] = $riderid;
                $data[$key]['dishes_type'] = 'Z';
            }
            $number = $orderDao->insertAll($data);
            if($number==count($newInsert)){
                $orderDao->commit();
                return true;
            }else{
                $orderDao->rollback();
                return false;
            }
        }else{
            $orderDao->commit();
            return true;
        }

    }
}
