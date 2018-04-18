<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/21 0021
 * Time: 上午 11:09
 */
namespace app\wechatweb\controller;
use think\Controller;
use think\Cookie;
use think\Session;

class User extends Controller
{
    //微信公众号获取用户信息回调
    public function index(){
        //获取openid
        $userModel = new \gyo2o\model\User();
        $userModel->getWechatUserInfo();
        $targeturl = Session::get('targeturl');
        if(!empty($targeturl)){
            $this->redirect($targeturl);
        }
        $eid = Cookie::get('eid');
        if(empty($eid)){
            $this->redirect(url('/wxchat/#/','',false,true));
        }else{
            $this->redirect(url('/wxchat/#/home','',false,true));
        }

    }

}