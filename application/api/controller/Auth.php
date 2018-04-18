<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/26 0026
 * Time: 下午 3:35
 */

namespace app\api\controller;
use fast\Http;
use gyo2o\model\Rider;
use \think\Controller;
use telephone\SMS;
use think\Session;


class Auth extends Common
{
    public function index(){
        header('Access-Control-Allow-Origin:*');
        Session::set('admin',12345);
        return json(['data'=>'true']);

    }



    //获取短信验证码
    public function telcode(){
        $result = ['code'=>10000,'status'=>'success'];
        do{
            $tel = $this->request->get('mobile');
            if(empty($tel)){
                $result= ['code'=>10001,'status'=>'failer'];
                break;
            }


            $rand_vcode = rand(99999, 1000000);

            $sms = new SMS();
            $csms = \think\Config::get('sms');
            $sms->sprdid = $csms['SMS_PRODUCT_ID'];
            $send = $sms->send('测试', '13437278639');var_dump($send);
        }while(0);
        return json($result);

    }

    //网页授权回调
    public function webAuthCallback(){

        $code = $this->request->get('code');
        $state = $this->request->get('state');

        //获取access_token和openid
        $wechatCon = \think\Config::get('wechat');
        $api = sprintf('https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code ',
            $wechatCon['WE_CHAT_APPID'],$wechatCon['WE_CHAT_APPSRCRET'],$code);
        $result = Http::get($api);
        $result = json_decode($result,true);var_dump($result);die;
        $openid = $result['openid'];

        //缓存
        cache($state,$openid,1800);

        //回跳到前端首页
        $this->redirect('');
    }

    //获取登录凭证
    public function getToken(){
        header('Access-Control-Allow-Origin:*');

        //唯一标示
        $unique_token = strtoupper(md5(uniqid(mt_rand(), true)));
        //回跳地址
        $reditect = url('webAuthCallback','','',true);
        //微信授权地址
        $wechatCon = \think\Config::get('wechat');
        $url = sprintf('https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=%s#wechat_redirect',
            $wechatCon['WE_CHAT_APPID'],urlencode($reditect),$unique_token);
        return $this->apireturn(['token'=>$unique_token,'url'=>$url]);
    }

    //骑手认证
    public function Authentication(){
        header('Access-Control-Allow-Origin:*');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods:POST');

        do{
            $userid = $this->request->post('userid');
            $idcard = $this->request->post(('idcard'));
            $result = array();
            if(empty($idcard)||empty($userid)){
                $this->errstr = '缺少参数';
                $this->code = 100001;
                break;
            }

            $riderModel = new Rider();
            $result = $riderModel->riderAuth($userid,$idcard);
            if(is_bool($result) && !$result){
                $this->errstr = '身份信息不存在';
                $this->code = 100001;
                break;
            }elseif(is_array($result)&&!empty($result['msg'])){
                $this->errstr = $result['msg'];
                $this->code = 100001;
                $result = [];
                break;
            }
            $result['site_name'] = get_site_name($result['blesite_id']);

        }while(0);
        return $this->apireturn($result);
    }

    public function login(){
        header('Access-Control-Allow-Origin:*');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods:POST');
        do{
            $reult = array();
            $token = $this->request->post('token');
            if(empty($token)){
                $this->errstr = '缺少参数';
                $this->code = 100001;
                break;
            }

            $openid = cache($token);
            if(empty($openid)){
                $this->errstr = 'token过期';
                $this->code = 100011;
                break;
            }

            //获取用户信息
            $riderModel = new Rider();
            $reult =$riderModel->getRiderByopenid($openid);
            if(!$reult){
                //未认证只返回openID
                $reult = array('open_id'=>$openid,'Id'=>0);
            }elseif ($reult['leavetime']){
                $this->errstr = '已离职';
                $this->code = 100001;
                $reult = array();
                break;
            }

        }while(0);
        return $this->apireturn($reult);
    }

    public function getJsConfig(){
        do{
            $url = $this->request->get('url');
            if(empty($url)){
                $this->errstr = '缺少URL参数';
                $this->code = 100001;
                $signature = array();
                break;
            }
            $wechat = \think\Config::get('wechat');
            $token = new \EasyWeChat\Core\AccessToken($wechat['WE_CHAT_APPID'],$wechat['WE_CHAT_APPSRCRET']);
            $jsapi = new \EasyWeChat\Js\Js($token);
            $signature = $jsapi->signature($url);
        }while(0);
        return $this->apireturn($signature);

    }

    public function isAuth(){
        header('Access-Control-Allow-Origin:*');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods:POST');
        do{
            $userid = $this->request->post('userid');
            $result = array();
            if(empty($userid)){
                $this->errstr = '缺少参数';
                $this->code = 100001;
                break;
            }
            $riderModel = new Rider();
            $result = $riderModel->getRiderByUserid($userid);
            if(!$result){
                //未认证只返回userid
                $result = array('userid'=>$userid,'Id'=>0);
            }elseif ($result['leavetime']||$result['type']=='已离职'){
                $this->errstr = '已离职';
                $this->code = 100001;
                $result = array();
                break;
            }
        }while(0);
        return $this->apireturn($result);

    }
}