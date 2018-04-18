<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27 0027
 * Time: 下午 2:22
 */

namespace app\api\controller;


use function fast\e;
use gyo2o\model\Rider;
use gyo2o\model\Site;
use think\Db;

class Order extends Common
{

    //预定
    public function reserve(){
        header('Access-Control-Allow-Origin:*');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods:POST');

        do{
            $riderid = $this->request->post('userid');
            $data = $this->request->post('datas/a');
            if(empty($riderid)){
                $this->errstr = '骑手Id为空';
                $this->code = 100001;
                break;
            }
            $rider = new Rider();
            if($rider->isLeave($riderid)){
                $this->errstr = '已离职不可点餐';
                $this->code = 100001;
                break;
            }
            if(empty($data)){
                $this->errstr = '订餐数据为空';
                $this->code = 100001;
                break;
            }
            if (!is_array($data)||count($data)!=7){
                $this->errstr = '订餐数据有误';
                $this->code = 100001;
                break;
            }

            //处理数据
            $insertData = array();
            foreach ($data as $val){
                $tmp = explode('-',$val,2);
                $insertData[] = array('rider_id'=>$riderid,'date'=>$tmp[1],'dishes_type'=>$tmp[0]);
            }
            //统计取消天数
            $count = 0;
            foreach ($insertData as &$value){
//                $value['rider_id'] = $riderid;
                if($value['dishes_type'] == 'Z'){
                    $count +=1;
                }
            }

            if($count >2){
                $this->errstr = '取消天数超过两天';
                $this->code = 100001;
                break;
            }

            //订单处理
            $orderModel = new \gyo2o\model\Order();
//            判断是否已经下过单
            if($orderModel->isReserve($insertData,$riderid)){
                $this->errstr = '本周已下单';
                $this->code = 100002;
                break;
            }
            $orderModel->startTrans();
            $result = $orderModel->insertAll($insertData);
            if($result!=7){
                $this->errstr = '下单失败，稍后再试';
                $this->code = 100001;
                $orderModel->rollback();
                break;
            }
            $orderModel->commit();

        }while(0);
        return $this->apireturn(array());
    }

    //骑手月订单列表
    public function riderOrder(){
        do{
            $riderid = $this->request->get('userid');
            $month = $this->request->get('month');
            $list = array();
            if(empty($riderid)||empty($month)){
                $this->errstr = '缺少参数';
                $this->code = 100001;
                break;
            }

            $ridermodel = new \gyo2o\model\Rider();
            $res = $ridermodel->riderorder($riderid,$month);
            $list = $res['list'];
            //统计已领未领数量
            $received = 0;
            $unreceived = 0;
            foreach ($list as $value){
                if($value['status']=='已领'){
                    $received ++;
                }

                if($value['status'] == '待领'){
                    $unreceived++;
                }
            }

        }while(0);

        return $this->apireturn(array('list'=>$list,'received'=>$received,'unreceived'=>$unreceived));

    }

    //站点骑手天订餐列表
    public function siteOrder(){
        do{
            $siteid = $this->request->get('siteid');
            $date = $this->request->get('date');
            $list = array();
            if(empty($siteid)){
                $this->errstr = '缺少参数';
                $this->code = 100001;
                break;
            }

            //按日期获取站点骑手订餐情况
            $orderModel = new \gyo2o\model\Order();
            $result = $orderModel->siteOrderWeek($siteid);
            $list = $result;


        }while(0);
        return $this->apireturn($list);
    }

    //扫码领餐
    public function codeScan(){
        do{
            $riderid = $this->request->get('userid');
            $siteid = $this->request->get('siteid');
            $return = array();
            if(empty($riderid)||empty($siteid)){
                $this->errstr = '缺少参数';
                $this->code = 100001;
                break;
            }
            $orderModel = new \gyo2o\model\Order();
            //判断领餐站点
            if(!$orderModel->siteCheck($siteid,$riderid)){
                $this->errstr = '不在当前领餐站点';
                $this->code = 100001;
                break;
            }
            $result = $orderModel->receive($riderid);
            if(!$result){
                $this->errstr = '系统原因取餐失败';
                $this->code = 100001;
                break;
            }
            if(is_array($result)){
                $this->errstr = $result['msg'];
                $this->code = 100001;
                break;
            }
            $this->errstr = '领餐成功';
        }while(0);
        return $this->apireturn($return);
    }

    //生成领餐二维码
    public function qrcode(){
        $siteid = $this->request->get('siteid');
        $url = url('codeScan',['siteid'=>$siteid],null,true);
        $qrCode = new \Endroid\QrCode\QrCode();
        $qrCode->setText($siteid)
            ->setSize(300)
            ->setPadding(10)
            ->setErrorCorrection('high')
            ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0])
            ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0])
            ->setLabelFontSize(16)
            ->setImageType(\Endroid\QrCode\QrCode::IMAGE_TYPE_PNG);

        // now we can directly output the qrcode
        header('Content-Type:'.$qrCode->getContentType());
        $qrCode->render();die;
    }

    //修改订餐
    public function editOrder(){
        header('Access-Control-Allow-Origin:*');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods:POST');
        do{
            $result = 0;
            $riderid = $this->request->post('userid');
            $data = $this->request->post('data');
            if(empty($riderid)||empty($data)){
                $this->errstr = '缺少参数';
                $this->code = 100001;
                break;
            }
            $data = explode('-',$data,2);
            $date = $data[1];
            $dishesType = $data[0];
            $orderModle = new \gyo2o\model\Order();
            if(!$orderModle->editOrder($riderid,$date,$dishesType)){
                $this->errstr = '修改失败';
                $this->code = 100001;
                break;
            }

        }while(0);
        return $this->apireturn($result);
    }

    public function orderInfo(){
        do{
            $result = array();
            $riderid = $this->request->get('userid');
            if(empty($riderid)){
                $this->errstr = '缺少参数';
                $this->code = 100001;
                break;
            }
            $orderModel = new \gyo2o\model\Order();
            $result = $orderModel->getOrderInfo($riderid);
            if(!$result){
                $this->errstr = '数据有误';
                $this->code = 100001;
                $result = array();
                break;
            }

        }while(0);
        return $this->apireturn($result);
    }

    public function getSits(){
        header('Access-Control-Allow-Origin:*');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods:POST');
        do{
            $siteModel = new Site();
            $result = $siteModel->getAll();

        }while(0);
        return $this->apireturn($result);
    }

    public function setSite(){
        header('Access-Control-Allow-Origin:*');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods:POST');
        do{
            $riderid = $this->request->post('userid');
            $siteid = $this->request->post('siteid');
            $reult = array();
            if(empty($riderid)||empty($siteid)){
                $this->errstr = '缺少参数';
                $this->code = 100001;
                break;
            }
            $riderModel = new Rider();
            if($riderModel->setSite($riderid,$siteid)===false){
                $this->errstr = '提交失败';
                $this->code = 100001;
                break;
            }

        }while(0);
        return $this->apireturn($reult);
    }

    public function  riderDayOrder(){
        header('Access-Control-Allow-Origin:*');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods:POST');
        do{
            $riderid = $this->request->post('userid');
            $date = $this->request->post('date');
            $result = array();
            if(empty($riderid)||empty($date)){
                $this->errstr = '缺少参数';
                $this->code = 100001;
                break;
            }
            $order = new \gyo2o\model\Order();
            $data = $order->riderDayOrder($riderid,$date);
            if($data){
                $result = $data;
                break;
            }
            $this->errstr = '未订餐';
            $this->code = 1000001;

        }while(0);
        return $this->apireturn($result);
    }
}