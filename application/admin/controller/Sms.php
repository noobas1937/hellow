<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/19 0019
 * Time: 下午 5:10
 */

namespace app\admin\controller;


use app\common\controller\Backend;
use gyo2o\dao\VerifyCodeDao;
use gyo2o\model\VerifyCode;
use Symfony\Component\HttpFoundation\Session\Session;
use think\Response;

class Sms extends Backend
{
    public function add()
    {
        $admin = \think\Session::get('admin');
        $mobile = $admin->mobile;
        $orginalUrl = $this->request->header('referer');
        if ($this->request->isPost())
        {
            $orginalUrl = $this->request->post('originalurl');
            $code = $this->request->post('code');
            $mobile = \think\Session::get('admin.mobile');
            $verify = new VerifyCodeDao();
            $codeData = $verify->getByAccount($mobile);
            if(!empty($codeData)&&$codeData['code']!=$code){
                $this->error('验证码错误','','',0);
            }
            if(strtotime($codeData['create_date'])+3600<=time()){
                $this->error('验证码已过期','','',0);
            }
            $this->success('验证码校验正确',$orginalUrl,'',0);


        }
        $this->assign('originalurl',$orginalUrl);
        $this->assign('mobile',$mobile);
        return $this->view->fetch();
    }

}