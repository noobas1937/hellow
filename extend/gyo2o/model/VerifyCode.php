<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/18 0018
 * Time: 上午 11:15
 */

namespace gyo2o\model;


use gyo2o\BaseModel;
use gyo2o\dao\UserAccountDao;
use telephone\SMS;
use gyo2o\dao\VerifyCodeDao;
use think\Session;

class VerifyCode extends BaseModel
{

    /** 手机短信验证码 **/
    public function GetTelphoneCode($mobile)
    {
        if (!empty($mobile)) {
            $user = new UserAccountDao();
            $isuser = $user->get_by_mobile($mobile);
            if (!empty($isuser)&&!empty($isuser['wx_openid']) && !empty($isuser['alipay_openid'])) {
                $array = array("status" => "failer", "code" => 4, "msg" => "手机号已存在");
            } else {

                $rand_vcode = rand(99999, 1000000);
                $msg = '您的验证码是' . $rand_vcode . '，感谢您成为踢踢奋斗注册用户！';
                $sms = new SMS();
                $csms = \think\Config::get('sms');
                $sms->sprdid = $csms['SMS_PRODUCT_ID'];
                $send = $sms->send($msg, $mobile);

                if ($send && $rand_vcode) {
                    $verify = new VerifyCodeDao();
                    $code_data['account'] = $mobile;
                    $code_data['code'] = $rand_vcode;
                    $code_data['create_date'] = date("Y-m-d H:i:s");
                    $insert_code = $verify->save($code_data);
                    $array = array("status" => "success", "code" => 3, "msg" => "发送成功");
//                    session('code',$rand_vcode,60);
                } else {
                    $array = array("status" => "failer", "code" => 4, "msg" => "手机验证码发送失败");
                }
            }

            return $array;
        }
    }


    public function get_mobile($mobile){
        $ver = new VerifyCodeDao();
        return $ver->getByAccount($mobile);
    }

    public function get_code($code){
        $ver = new VerifyCodeDao();
        return $ver->get_code($code);
    }

    public function set_status($id,$status){
        $ver = new VerifyCodeDao();
        return $ver->set_status($id,$status);
    }

    //发送管理员后台操作验证码
    public function getAdminTelCode(){
        $mobile = Session::get('admin.mobile');
        if (!empty($mobile)){
            $verify = new VerifyCodeDao();
            $codeDate = $verify->getByAccount($mobile);
            if(!empty($codeDate)&&(strtotime($codeDate['create_date'])+3600)>time()){
                $array = array("status" => "success", "code" => 3, "msg" => "验证码还在有效期内");
                return $array;
            }

            //重新发送验证码
            $rand_vcode = rand(99999, 1000000);
            $msg = '您的后台操作验证码是' . $rand_vcode . '，该验证码一小时内可重复使用！';
            $sms = new SMS();
            $csms = \think\Config::get('sms');
            $sms->sprdid = $csms['SMS_PRODUCT_ID'];
            $send = $sms->send($msg, $mobile);

            if ($send && $rand_vcode) {
                $code_data['account'] = $mobile;
                $code_data['code'] = $rand_vcode;
                $code_data['create_date'] = date("Y-m-d H:i:s");
                $code_data['type'] = 2;
                $insert_code = $verify->save($code_data);
                $array = array("status" => "success", "code" => 3, "msg" => "发送成功");
//                    session('code',$rand_vcode,60);
            } else {
                $array = array("status" => "failer", "code" => 4, "msg" => "手机验证码发送失败");
            }


            return $array;

        }

    }
}