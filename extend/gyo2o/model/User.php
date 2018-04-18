<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/18 0018
 * Time: 下午 2:03
 */

namespace gyo2o\model;


use EasyWeChat\Foundation\Application;
use gyo2o\BaseModel;
use gyo2o\dao\attachmentDao;
use gyo2o\dao\DataDictionaryDao;
use gyo2o\dao\EmployeeApplyDao;
use gyo2o\dao\EmployeeDao;
use gyo2o\dao\UserAccountDao;
use gyo2o\dao\UserStatusDao;
use gyo2o\dao\VerifyCodeDao;
use gyo2o\dao\UserInfoDao;
use think\Config;
use think\Cookie;
use think\Session;

class User extends BaseModel
{

    /** 修改用户手机号 **/
    public function get_mobile($post_data)
    {
        $user_count = new UserAccountDao();
        $code = new VerifyCodeDao();
        $code_data = $code->getByAccount($post_data['mobile']);
        $employeeDao = new EmployeeDao();
        if ($code_data['code'] == $post_data['code']) {
//            if(strtotime($code_data['create_date'])+160 < time()){
//                $array = array("status" => "success", "code" => 4, "msg" => "验证码已过期");
//                return $array;
//            }
            $account = $user_count->get_by_mobile($post_data['mobile']);
//            if($account['mobile']){
//                $array = array("status" => "success", "code" => 4, "msg" => "电话号码已存在");
//                return $array;
//            }
            $user_count->startTrans();
            $employee = $employeeDao->find($post_data['user_id']);
            $employeeResult = $employee->save(['contact_moblie'=>$post_data['mobile']]);
//            $result = $user_count->set_mobile($employee['tb_user_id'], $post_data['mobile']);
            if ($employeeResult) {
                $array = array("status" => "success", "code" => 3, "msg" => "修改成功");
                $user_count->commit();
            } else {
                $array = array("status" => "failer", "code" => 4, "msg" => "修改失败");
                $user_count->rollback();
            }
        } else {
            $array = array("status" => "failer", "code" => 4, "msg" => "验证码错误，请重新输入验证码");
        }
        return $array;
    }

    /** 修改用户昵称 **/
    public function set_nickname($nickname,$userid)
    {
        $user_info = new UserInfoDao();
        $result = $user_info->nickname($userid, $nickname);
        if ($result!==false) {
            $array = array("status" => "success", "code" => 3, "msg" => "修改成功", "url" => Url('member/setting/index'));
        } else {
            $array = array("status" => "failer", "code" => 4, "msg" => "修改失败");
        }
        return $array;
    }

    /** 修改用户性别 **/
    public function set_sex($sex,$userid)
    {
        $user_info = new UserInfoDao();
        $result = $user_info->sex($userid, $sex);
        if ($result !== false) {
            $array = array("status" => "success", "code" => 3, "msg" => "修改成功");
        } else {
            $array = array("status" => "failer", "code" => 4, "msg" => "修改失败");
        }
        return $array;
    }

    /**
     * 设置用户头像
     * @param $img
     *
     * @return array
     */
    public function set_head_img($img,$userid)
    {
        $array = array('status' => 'failer', 'code' => 0, 'msg' => null, 'output' => null);
        if ($img && 0 < strlen($img)) {

            if ($img) {
                $userInfo = new UserInfoDao();
                if ($userInfo->where(array('account_id' => $userid))->setField('head_img', $img))
                    $array = array("status" => "success", "code" => 100000, "msg" => "设置成功");
            } else
                $array = array("status" => "failer", "code" => 200000, "msg" => "设置失败");
        } else
            $array = array("status" => "failer", "code" => 300000, "msg" => "未选择图片");
        return $array;
    }

    /** 修改用户生日 **/
    public function set_birth($birth,$userid)
    {
        $user_info = new UserInfoDao();
        $result = $user_info->birth($userid, $birth);
        if ($result !== false) {
            $array = array("status" => "success", "code" => 3, "msg" => "修改成功");
        } else {
            $array = array("status" => "failer", "code" => 4, "msg" => "修改失败");
        }
        return $array;
    }

    public function get_openid($param){
        $user = new UserAccountDao();
        $user_info = [];

        if($param['client'] == 'wx'){
            $data['wx_openid'] = $param['openid'];
            $user_info = $user->get_by_openid($param['openid']);
        }
        if($param['client'] == 'alipay'){
            $data['alipay_openid'] = $param['openid'];
            $user_info = $user->get_by_alipay_openid($param['openid']);
        }
        if(sizeof($user_info)>0){
            $user_info_dao = new UserInfoDao();
            $user_status = new UserStatusDao();
            $user_info['status'] = $user_status->get_by_userid($user_info['id']);
            $user_info['info'] = $user_info_dao->get_by_user_id($user_info['id']);
            if($user_info['info']){
                $att = new attachmentDao();
                $user_info['info']['img_url'] = $att->getUrlAttr($user_info['info']['head_img']);
            }

        }else{
            $data['create_date'] =  date('Y-m-d H:i:s');
            $user_info['id'] = $user->insertGetId($data);

        }
        return $user_info;
    }

    public function set_mobile($param){
        $user = new UserAccountDao();
        $code = new VerifyCodeDao();
        $code_data = $code->getByAccount($param['mobile']);
        if ($code_data['code'] != $param['code']) {
            return '验证码错误';
        }
        $user_account = $user->get_by_mobile($param['mobile']);
        if($param['client'] == 'wx'){
            $data['wx_openid'] = $param['openid'];
        }
        if($param['client'] == 'alipay'){
            $data['alipay_openid'] = $param['openid'];
        }
        if($user_account){
            if($user_account['wx_openid']&&$param['client'] == 'wx'){
                return '此用户已绑定微信';
            }
            if($user_account['alipay_openid']&&$param['client'] == 'alipay'){
                return '此用户已绑定支付宝';
            }
            $result = $user->set_openid(['id'=>$user_account['id']],$data);
            if($result){
                $user_info_dao = new UserInfoDao();
                $user_info = $user_info_dao->get_by_user_id($user_account['id']);
                if(!$user_info['nickname']){
                    $user_info_dao->nickname($user_account['id'],$param['nickname']);
                }
            }
            return $user_account;
        }else{
            $data['create_date'] =  date('Y-m-d H:i:s');
            $data['mobile'] = $param['mobile'];
            $result['id'] = $user->insertGetId($data);
            if($result['id']){
                $user_info_dao = new UserInfoDao();
                $info_data = [
                    'account_id' => $result['id'],
                    'nickname' => $param['nickname']
                ];
                $user_info_dao->insert($info_data);
            }
            return $result;
        }

    }

    public function bindByIdcard($idcard,$mobile,$codem,$openid,$client,$nickname){

        $user = new UserAccountDao();
        $employeeDao = new EmployeeDao();
        $employee = $employeeDao->getByIdcard($idcard);
        if(!empty($employee['tb_user_id'])){
            //此用户已经绑定
            return '此用户已绑定';
        }
//        $isexist = $employeeDao->get_by_mobile($mobile);
//        if(!empty($isexist) && $isexist['idcard'] != $idcard){
//            return '此电话号码已存在';
//        }
        $code = new VerifyCodeDao();
        $code_data = $code->getByAccount($mobile);
        if ($code_data['code'] != $codem) {
            return '验证码错误';
        }
        $user_account = $user->get_by_openid($openid);
        if($client == 'wx'){
            $data['wx_openid'] = $openid;
        }
        if($client == 'alipay'){
            $data['alipay_openid'] = $openid;
        }
        $data['mobile'] = $mobile;
        if($user_account){
//            if($user_account['mobile']){
//                return '此用户已绑定';
//            }

            $result = $user->set_openid(['id'=>$user_account['id']],$data);
            if($result){
                $user_info_dao = new UserInfoDao();
                $user_info = $user_info_dao->get_by_user_id($user_account['id']);
                if(!$user_info['nickname']){
                    $user_info_dao->nickname($user_account['id'],$nickname);
                }
            }
            $accountid =  $user_account['id'];
        }else{
            $data['create_date'] =  date('Y-m-d H:i:s');
            $result['id'] = $user->insertGetId($data);
            if($result['id']){
                $user_info_dao = new UserInfoDao();
                $info_data = [
                    'account_id' => $result['id'],
                    'nickname' => $nickname
                ];
                $user_info_dao->insert($info_data);
            }
            $accountid = $result['id'];
        }

        $employee['contact_moblie'] = $mobile;
        $employee['tb_user_id'] = $accountid;
        $employee->save();
        return ['id'=>$accountid];
    }

    public function get_user_id($param){
        $user = new UserAccountDao();
        $user_info = $user->get_by_id($param['user_id']);
        if($user_info){
            $user_info_dao = new UserInfoDao();
            $user_status = new UserStatusDao();
            $user_info['status'] = $user_status->get_by_userid($user_info['id']);
            $user_info['info'] = $user_info_dao->get_by_user_id($user_info['id']);
            if($user_info['info']){
                $att = new attachmentDao();
                $user_info['info']['img_url'] = $att->getUrlAttr($user_info['info']['head_img']);
                $dataDicitionary = new DataDictionaryDao();
                if($user_info['status'])
                    $user_info['status']['city_name'] = $dataDicitionary->getName($user_info['status']['city_id']);
            }

        }
        return $user_info;
    }

    public function getWechatUserInfo(){
        $config = Config::get('wechat');
        $config = [
            'debug'     => $config['WE_CHAT_XZTT_DEBUG'],
            'token'     => $config['WE_CHAT_XZTT_TOKEN'],
            'app_id'    => $config['WE_CHAT_XZTT_APPID'],
            'secret'    => $config['WE_CHAT_XZTT_APPSRCRET'],
        ];
        $app = new Application($config);
        if(empty($_GET['code'])){
            //未授权重新授权
            $targeturl = !empty($_GET['targeturl'])?$_GET['targeturl']:'';
            Session::set('targeturl',$targeturl);
           $app->oauth->scopes(['snsapi_userinfo'])->redirect(url('wechatweb/user/index','','',true))->send();
           die();
        }else{
            //已经授权获取用户信息

            $outh = $app->oauth;
            $userinfo = $outh->user();
            $userinfo = $userinfo->toArray();
            Session::set('wechatuser',$userinfo);
            $userAccountDao = new UserAccountDao();
            if(!empty($userinfo['id'])){
                $account = $userAccountDao->get_by_openid($userinfo['id']);
                if(empty($account)){
                    //添加account账户
                    $account['wx_openid'] = $userinfo['id'];
                    $account['create_date'] = date('Y-m-d H:i:s');
                    $account['id'] = $userAccountDao->insertGetId($account);
                    $employeeid = 0;
                }else{
                    $employeeid = $account['employee_id'];
                }
                if(empty($employeeid)){
                    //判断员工是否已提交注册信息
                    $employeeApplyDao = new EmployeeApplyDao();
                    $applyinfo = $employeeApplyDao->getByUserId($account['id']);
                    if(empty($applyinfo)){
                        //没有提交认证
                        $employeeid = 0;
                    }else{
                        //已提交审核
                        $employeeid = (0 - $applyinfo['id']);
                    }
                }
                Cookie::set('uid',$account['id']);
                Cookie::set('eid',$employeeid);
            }

        }

    }

    public function userBind($uid,$eid,$mobile){

        $userAccountDao = new UserAccountDao();
        $account = $userAccountDao->where(['employee_id'=>$eid])->find();
//        if(!empty($account['employee_id'])){
//            $this->error =  '已与其它账号绑定';
//            return false;
//        }

        //绑定
        $userAccountDao->where(['id'=>$uid])->setField('employee_id',$eid);
        $employeeDao = new EmployeeDao();
        $employeeDao->where(['id'=>$eid])->setField('contact_moblie',$mobile);
        Cookie::set('eid',$eid);
        Session::set('eid',$eid);
        return true;
    }

    public function newget_openid($param){
        $user = new UserAccountDao();
        $user_info = [];

        if($param['client'] == 'wx'){
            $data['wx_openid'] = $param['openid'];
            $user_info = $user->get_by_openid($param['openid']);
        }
        if($param['client'] == 'alipay'){
            $data['alipay_openid'] = $param['openid'];
            $user_info = $user->get_by_alipay_openid($param['openid']);
        }
        if(sizeof($user_info)>0){
//            $user_info_dao = new UserInfoDao();
//            $user_status = new UserStatusDao();
//            $user_info['status'] = $user_status->get_by_userid($user_info['id']);
//            $user_info['info'] = $user_info_dao->get_by_user_id($user_info['id']);
//            if($user_info['info']){
//                $att = new attachmentDao();
//                $user_info['info']['img_url'] = $att->getUrlAttr($user_info['info']['head_img']);
//            }
            if(empty($user_info['employee_id'])){
                //
                $employeeDao = new EmployeeDao();
                $employeeinfo = $employeeDao->getByUid($user_info['id']);
                if(empty($employeeinfo)){
                    $user_info['employee_id'] = 0;
                }else{
                    $user_info['employee_id'] = $employeeinfo['id'];
                    $user_info->save();
                }
            }

        }else{
            $data['create_date'] =  date('Y-m-d H:i:s');
            $user_info['id'] = $user->insertGetId($data);
            $user_info['employee_id'] = 0;

        }
        return $user_info;
    }

}