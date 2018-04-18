<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27 0027
 * Time: 下午 3:05
 */
namespace app\lucky\controller;
use app\common\controller\Api;
use gyo2o\dao\EmployeeDao;
use gyo2o\dao\LuckyDrawAwardDao;
use gyo2o\model\LuckyDraw;
use gyo2o\model\LuckyDrawRecord;
use gyo2o\model\LuckyTicketRecordModel;
use fast\Http;

class Ajax extends Newyear
{
    //大转盘奖品信息
    public function luckyDrawInfo(){

        $luckyDrawId = $this->request->post('draw_id');
        $userid = $this->request->post('user_id');
        $isTicket = $this->request->post('isticket');
        //年会活动区分场内场外
        $luckyDrawModel = new LuckyDraw();
        if(!empty($isTicket)){
            $draw = $luckyDrawModel->innerDrawid($userid);
            if(!empty($draw)){
                $luckyDrawId = $draw;
            }else{
                return json(["status" => "failer", "code" => 4, "msg" => "年会活动未设置"]);
            }
        }
        if (empty($luckyDrawId) || empty($userid)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }

        $luckyDrawModel = new \gyo2o\model\LuckyDraw();
        $result = $luckyDrawModel->getAwardByLuckyDrawId($luckyDrawId);
        if($result){
            return json(["status" => "success", "code" => 3, "msg" => "","data"=>array_values($result)]);
        }

        return json(["status" => "failer", "code" => 4, "msg" => "数据有误"]);
    }




    //大转盘抽奖
    public function luckyDraw(){
        return $this->luckyDrawExt();

        //$luckyDrawId = $this->request->post('draw_id');

        $isTicket = $this->request->post('isticket');
        //年会活动区分场内场外
        $luckyDrawModel = new LuckyDraw();
        if(!empty($isTicket)){
            $draw = $luckyDrawModel->innerDrawid($userid);
            if(!empty($draw)){
                $luckyDrawId = $draw['draw_id'];
            }else{
                return json(["status" => "failer", "code" => 4, "msg" => "年会活动未开始"]);
            }
        }
        if (empty($luckyDrawId) || empty($userid)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }

        $result = $luckyDrawModel->luckyDraw($luckyDrawId,$userid,$isTicket);
        if($result){
            return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$result]);
        }else{
            return json(["status" => "failer", "code" => 4, "msg" => $luckyDrawModel->getError()]);
        }
    }



    public function newyearDrawInfo(){
        return $this->newyearDrawInfoExt();
        //return json(['status'=>'success','code'=>3,'data'=>['type' => 0,'time' => ['day' => 1 ,'hour'=> 1,'minute'=> 1,'second' => 1]]]);

    }



    //积分奖品领奖
    public function getPrize(){
        $userid = $this->request->post('user_id');
        $awarid = $this->request->post('award_id');
        $recordid = $this->request->post('record_id');
        if (empty($userid) || empty($awarid)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }

        $luckyDrawModel = new LuckyDraw();
        $result = $luckyDrawModel->getPrize($recordid,$userid,$awarid);
        if($result){
            return json(["status" => "success", "code" => 3, "msg" => ""]);
        }

        return json(["status" => "failer", "code" => 4, "msg" => "领取失败"]);
    }

    //积分夺宝报名
    public function luckyApply(){
        $luckyDrawId = $this->request->post('draw_id');
        $userid = $this->request->post('user_id');
        $number = $this->request->post('number');
        if(empty($luckyDrawId) || empty($userid) || empty($number)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }

        $luckyDrawModel = new LuckyDraw();
        $result = $luckyDrawModel->luckyApply($userid,$luckyDrawId,$number);
        if($result){
            return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$result]);
        }else{
            return json(["status" => "failer", "code" => 4, "msg" => $luckyDrawModel->getError()]);
        }
    }

    //积分夺宝活动信息
    public function luckyApplyInfo(){
        $luckyDrawId = $this->request->post('draw_id');
        $userid = $this->request->post('user_id');
        if(empty($luckyDrawId)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }
        if(empty($userid)){
            $userid = 0;
        }
        $luckyDrawModel = new LuckyDraw();
        $result = $luckyDrawModel->getluckyApplyInfo($userid,$luckyDrawId);
        if($result){
            return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$result]);
        }else{
            return json(["status" => "failer", "code" => 4, "msg" => $luckyDrawModel->getError()]);
        }
    }

    //最新中奖纪录
    public function luckyPrizeRecord(){
        $luckyDrawId = $this->request->post('draw_id');
        $isTicket = $this->request->post('isticket');
        $userid = $this->request->post('user_id');
        //年会活动区分场内场外
        $luckyDrawModel = new LuckyDraw();
        if(!empty($isTicket)){
            $draw = $luckyDrawModel->innerDrawid($userid);
            if(!empty($draw)){
                $luckyDrawId = $draw;
            }else{
                return json(["status" => "failer", "code" => 4, "msg" => "年会活动未设置"]);
            }
        }
        if(empty($luckyDrawId)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }

        $luckyDrawModel = new LuckyDraw();
        $result = $luckyDrawModel->getPrizeRecord($luckyDrawId);
        if($result!==false){
            return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$result]);
        }else{
            return json(["status" => "failer", "code" => 4, "msg" => $luckyDrawModel->getError()]);
        }
    }

    //所有积分夺宝历史开奖
    public function luckyHistory(){
        $page = $this->request->get('page');
        $pageSize = $this->request->get('pagesize');
        if(empty($page)){
            $page = 0;
        }

        if(empty($pageSize)){
            $pageSize = 30;
        }
        $luckyDrawModel = new LuckyDraw();
        $result = $luckyDrawModel->luckyHistory($page,$pageSize);
        if($result!==false){
            return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$result]);
        }else{
            return json(["status" => "failer", "code" => 4, "msg" => $luckyDrawModel->getError()]);
        }
    }

    //单个抽奖活动所有中奖纪录
    public function luckDrawPrizeRecord(){
        $page = $this->request->get('page');
        $pageSize = $this->request->get('pagesize');
        $userid = $this->request->get('user_id');
        $isTicket = $this->request->get('isticket');
        $isrecord = $this->request->get('isrecord');
        if(empty($page)){
            $page = 0;
        }

        if(empty($pageSize)){
            $pageSize = 100;
        }
        $drawid = $this->request->get('draw_id');
        //年会活动区分场内场外
        $luckyDrawModel = new LuckyDraw();
//        if(!empty($isTicket)){
//            $draw = $luckyDrawModel->innerDrawid($userid);
//            if(!empty($draw)){
//                $drawid = $draw;
//            }else{
//                return json(["status" => "failer", "code" => 4, "msg" => "年会活动未设置"]);
//            }
//        }

        if(empty($drawid)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }
        $luckyDrawModel = new LuckyDraw();
        $result = $luckyDrawModel->luckDrawPrizeRecord($drawid,$page,$pageSize,$isrecord);
        if($result!==false){
            return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$result]);
        }else{
            return json(["status" => "failer", "code" => 4, "msg" => $luckyDrawModel->getError()]);
        }
    }

    //最新两条夺宝信息
    public function getLastLuckyApplyInfo(){
        $limit = $this->request->get('limit');
        if(empty($limit)){
            $limit = 2;
        }
        $luckyDrawModel = new LuckyDraw();
        $result = $luckyDrawModel->getLastLuckyApplayInfo($limit);
        if($result){
            return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$result]);
        }else{
            return json(["status" => "failer", "code" => 4, "msg" => $luckyDrawModel->getError()]);
        }
    }

    //我的夺宝纪录
    public function luckyApplyRecord(){
        $page = $this->request->get('page');
        $pageSize = $this->request->get('pagesize');
        $userid = $this->request->get('user_id');
        if(empty($page)){
            $page = 0;
        }

        if(empty($pageSize)){
            $pageSize = 10;
        }
        $luckyDrawModel = new LuckyDraw();
        $result = $luckyDrawModel->luckyApplyRecord($userid,$page,$pageSize);
        if($result!==false){
            return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$result]);
        }else{
            return json(["status" => "failer", "code" => 4, "msg" => $luckyDrawModel->getError()]);
        }
    }

    //我的中奖码
    public function getLuckyNumber(){
        $drawid = $this->request->post('draw_id');
        $userid = $this->request->post('user_id');
        if(empty($drawid) || empty($userid)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }

        $luckyDrawModel = new LuckyDraw();
        $result = $luckyDrawModel->getLuckyNumber($drawid,$userid);
        if($result!==false){
            return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$result]);
        }else{
            return json(["status" => "failer", "code" => 4, "msg" => $luckyDrawModel->getError()]);
        }
    }



    //获取用户的抽奖券
    public function getLuckyTicket(){
        return $this->getLuckyTicketExt();


        $userid = $this->request->post('user_id') or $userid = 259;
        $drawid = $this->request->post('draw_id')?:2;


        $luckyDrawModel = new LuckyDraw();
        $draw = $luckyDrawModel->innerDrawid($userid);
        if(!empty($draw)){
            $drawid = $draw;
        }else{
            return json(["status" => "failer", "code" => 4, "msg" => "年会活动未设置"]);
        }

        if(empty($userid) || empty($drawid)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }

        $luckyTicketModel = new LuckyTicketRecordModel();
        //发放抽奖券
        $luckyTicketModel->fiveTicket($userid,$drawid);
        $result = $luckyTicketModel->getByUserid($userid,$drawid);
        if($result!==false){
            return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$result]);
        }else{
            return json(["status" => "failer", "code" => 4, "msg" => $luckyTicketModel->getError()]);
        }

    }

    //获取用户openid
    public function getOpenid(){
        $code = $this->request->post('code');
        $istt = $this->request->post('istt');
        //获取access_token和openid
        $wechatCon = \think\Config::get('wechat');
        if(empty($istt)){
            $api = sprintf('https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',
                $wechatCon['WE_XCX_APPID'],$wechatCon['WE_XCX_APPSRCRET'],$code);
        }else{
            $api = sprintf('https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',
                $wechatCon['WE_XCX_XZTT_APPID'],$wechatCon['WE_XCX_XZTT_APPSRCET'],$code);
        }

        $result = Http::get($api);
        $result = json_decode($result,true);
        $openid = $result['openid'];
        return json(['openid'=>$openid]);
    }

    //新积分夺宝报名
    public function newLuckyApply(){
        $luckyDrawId = $this->request->post('draw_id');
        $userid = $this->request->post('user_id');
        $number = $this->request->post('number');
        $ticketnumber = $this->request->post('ticketnumber');
        if(empty($luckyDrawId) || empty($userid) || empty($number)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }
        if(empty($ticketnumber)){
            $ticketnumber = 0;
        }
        if($number - $ticketnumber < 0){
            return json(["status" => "failer", "code" => 4, "msg" => "购买份数比夺宝券少"]);
        }
        $luckyDrawModel = new LuckyDraw();
        $result = $luckyDrawModel->newLuckyApply($userid,$luckyDrawId,$number,$ticketnumber);
        if($result){
            return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$result]);
        }else{
            return json(["status" => "failer", "code" => 4, "msg" => $luckyDrawModel->getError()]);
        }
    }

    //夺宝参数计算
    public function luckyParam(){
        $drawid = intval($this->request->post('draw_id'));
        $userid = intval($this->request->post('user_id'));
        $number = intval($this->request->post('number'));
        $ticketnumber = intval($this->request->post('ticketnumber'));
        if(empty($drawid) || empty($userid)){
            return json(["status" => "failer", "code" => 4, "msg" => '缺少参数']);
        }
        $luckyDrawModel = new LuckyDraw();
        $result = $luckyDrawModel->luckyParam($drawid,$userid,$number,$ticketnumber);
        if($result){
            return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$result]);
        }else{
            return json(["status" => "failer", "code" => 4, "msg" => $luckyDrawModel->getError()]);
        }
    }

}