<?php
namespace app\user\controller;

use app\common\controller\Api;
use app\common\model\Config;
use gyo2o\dao\EmployeeDao;
use gyo2o\dao\LuckyDrawRecordDao;
use gyo2o\model\Dictionary;
use gyo2o\model\EmployeeApply;
use gyo2o\model\EmployeeBank;
use gyo2o\model\EmployeeSalary;
use gyo2o\model\Evals;
use gyo2o\model\LuckyDrawRecord;
use gyo2o\model\Salary;
use gyo2o\model\TbEmployee;
use gyo2o\model\User;
use gyo2o\model\UserAddress;
use gyo2o\model\VerifyCode;
use think\Loader;
use gyo2o\dao\VerifyCodeDao;
use think\Session;


class Ajax extends Api
{
    public function user_address()
    {
        $param = input('post.');
        $validate = $this->validate($param,'user/UserValidate.user_id');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new UserAddress();
            $result = $class->get_by_address($param);
            $result = $this->return_list($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function get_openid(){
        $param = input('post.');
        $validate = $this->validate($param,'user/UserValidate.get_openid');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new User();
            $result = $class->get_openid($param);
            $result = $this->return_dao($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function get_user_id(){
        $param = input('post.');
        $validate = $this->validate($param,'user/UserValidate.user_id');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new User();
            $result = $class->get_user_id($param);
            $result = $this->return_dao($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function set_mobile(){
        $param = input('post.');
        $validate = $this->validate($param,'user/UserValidate.get_openid');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new User();
            $result = $class->set_mobile($param);
            $result = $this->return_dao($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function add_address()
    {
        $param = input('post.');
        $validate = $this->validate($param,'user/UserValidate.add_address');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new UserAddress();
            $result = $class->add_address($param);
            $result = $this->return_dao($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function remove_address()
    {
        $param = input('post.');
        $validate = $this->validate($param,'user/UserValidate.remove_address');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new UserAddress();
            $result = $class->del_data($param);
            $result = $this->return_dao($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function save_address()
    {
        $param = input('post.');
        $validate = $this->validate($param,'user/UserValidate.remove_address');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new UserAddress();
            $result = $class->set_data($param);
            $result = $this->return_dao($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function address_detail()
    {
        $param = input('post.');
        $validate = $this->validate($param,'user/UserValidate.remove_address');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new UserAddress();
            $result = $class->get_info($param);
            $result = $this->return_dao($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function province_list()
    {
        $param = input('post.');
        $validate = $this->validate($param,'user/UserValidate.user_id');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new Dictionary();
            $result = $class->get_by_address('PROVINCE');
            $result = $this->return_dao($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function get_pid()
    {
        $param = input('post.');
        $validate = $this->validate($param,'user/UserValidate.city_list');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new Dictionary();
            $result = $class->get_by_area($param['pid']);
            $result = $this->return_dao($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function set_user_status()
    {
        $param = input('post.');
        $validate = $this->validate($param,'user/UserValidate.add_user_status');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new UserAddress();
            $result = $class->add_user_status($param);
            $result = $this->return_dao($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function telcode()
    {
        $mobile = $this->request->post('mobile');
        if(empty($mobile)){
            return json(["status" => "failer", "code" => 4, "msg" => "手机号错误"]);
        }
        $verifycode = new VerifyCode();
        $result = $verifycode->GetTelphoneCode($mobile);
        return json($result);
    }

    public function setmobile()
    {
        $post_data = $this->request->post();
        if(empty($post_data['user_id'])||empty($post_data['code'])||empty($post_data['mobile'])){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }
        $user = new User();
        $result = $user->get_mobile($post_data);
        return json($result);
    }

    public function setnickname()
    {
        $user = new User();
        $nickname = $this->request->post('nickname');
        $userid = $this->request->post('user_id');
        if(empty($userid)||empty($nickname)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }
        $resutl = $user->set_nickname($nickname,$userid);
        return json($resutl);
    }

    public function setsex()
    {
        $user = new User();
        $sex = $this->request->post('sex');
        $userid = $this->request->post('user_id');
        if(empty($sex)||empty($userid)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }
        $resutl = $user->set_sex($sex,$userid);
        return json($resutl);
    }

    public function set_head_img()
    {
        $img = $this->request->post('img_id');
        $userid = $this->request->post('user_id');
        if(empty($img)||empty($userid)){
            return json([["status" => "failer", "code" => 4, "msg" => "缺少参数"]]);
        }
        $user = new User();
        $result = $user->set_head_img($img,$userid);
        return json($result);
    }

    public function setbirth()
    {
        $user = new User();
        $birth = $this->request->post('birth');
        $userid = $this->request->post(('user_id'));
        if(empty($userid)|| empty($birth)){
            return json([["status" => "failer", "code" => 4, "msg" => "缺少参数"]]);
        }
        $resutl = $user->set_birth($birth,$userid);
        return json($resutl);
    }

    //获取支付宝唯一用户ID
    public function aliPlaySing(){
        Loader::import('alipay.aop.AopClient');
        Loader::import('alipay.aop.request.AlipayOpenPublicTemplateMessageIndustryModifyRequest');
        Loader::import(('alipay.aop.request.AlipaySystemOauthTokenRequest'));
        $alipayConfig = \think\Config::get('alipay');
        $code = $this->request->post('code');
        $c = new \AopClient;
        $c->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $c->appId = $alipayConfig['APP_ID'];
        $c->rsaPrivateKey = $alipayConfig['RSAPRIVATEKEY'];
        $c->format = "json";
        $c->charset= "UTF-8";
        $c->signType= "RSA2";
        $c->alipayrsaPublicKey = $alipayConfig['ALIPAYRSAPUBLICKEY'];
        $request = new \AlipaySystemOauthTokenRequest ();
        $request->setGrantType("authorization_code");
        $request->setCode($code);
//        $request->setRefreshToken("201208134b203fe6c11548bcabd8da5bb087a83b");
        $result = $c->execute ( $request);

         return json($result);
    }

    public function evalList(){
        $userid = $this->request->post('user_id');
        $page = empty($this->request->post('page'))?1:$this->request->post('page');
        $pageSize = empty($this->request->post('pagesize'))?10:$this->request->post('pagesize');
        if(empty($userid)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }

        $evalModel = new Evals();
        $count = $evalModel->getUserCount($userid);
        if(empty($count)){
            return json(["status" => "success", "code" => 3, "msg" => "",'data'=>[]]);
        }
        $list = $evalModel->get_list($userid,$page,$pageSize);
        return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$list]);
    }

    public function getEmployee(){
        $user_id = $this->request->post('user_id');
//        $mobile = $this->request->post('mobile');
        if(empty($user_id)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }

        $employeeModel = new TbEmployee();
        $result = $employeeModel->bindEmployeeToUser($user_id);
        if($result){
            return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$result]);
        }else{
            return json(["status" => "failer", "code" => 4, "msg" => "不是员工"]);
        }
    }

    //用户在单个抽奖活动中奖纪录
    public function getLuckDrawRecord(){
        $employeeid = $this->request->post('user_id');
        $drawid = $this->request->post('draw_id');
        if(empty($employeeid) || empty($drawid)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }

        $luckDrawRecord = new LuckyDrawRecord();
        $result = $luckDrawRecord->getUserRecoud($employeeid,$drawid);
        return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$result]);
    }

    //用户所有抽奖活动中奖纪录
    public function getAllDrawRecord(){
        $userid = $this->request->post('user_id');
        $page = $this->request->get('page');
        $pageSize = $this->request->get('pagesize');
        if(empty($page)){
            $page = 0;
        }

        if(empty($pageSize)){
            $pageSize = 10;
        }
        if(empty($userid)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }
        $luckDrawRecord = new LuckyDrawRecord();
        $result = $luckDrawRecord->getAllDrawRecoud($userid,$page,$pageSize);
        return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$result]);
    }

    //员工确认信息
    public function getUserinfo(){
        $idcard = $this->request->post('idcard');
        if(empty($idcard)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }

        $employeeModel = new TbEmployee();
        $result = $employeeModel->getByIdcard($idcard);
        if(empty($result)){
            return json(["status" => "failer", "code" => 4, "msg" => "员工信息不存在"]);
        }else{
            return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$result]);
        }
    }

    public function userInfoConfirm(){
        $idcard = $this->request->post('idcard');
        $mobile = $this->request->post('mobile');
        $code = $this->request->post('code');
        $openid = $this->request->post('openid');
        $client = $this->request->post('client');
        $nickname = $this->request->post('nickname');
        if(empty($idcard) || empty($mobile) || empty($code) || empty($openid) || empty($client) || empty($nickname)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }

        $class = new User();
        $result = $class->bindByIdcard($idcard,$mobile,$code,$openid,$client,$nickname);
        $result = $this->return_dao($result);
        return json($result);
    }

    public function userCenter(){
        $userid = $this->request->get('user_id');
        if(empty($userid)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }
        $employeeModel = new TbEmployee();
        $result = $employeeModel->userCenter($userid);
        return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$result]);
    }

    //兑换并提现
    public function  conversion(){
        $userid = $this->request->post('user_id');
        $money = $this->request->post('money');
        if(empty($userid) || empty($money)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }
        if($money < 0){
            return json(["status" => "failer", "code" => 4, "msg" => "兑换金额不能为负数"]);
        }
        $employeeModel = new TbEmployee();
        $result = $employeeModel->conversion($userid,$money);
        if($result){
            $result = $employeeModel->withdraw($userid,$money);
            if($result){
                return json(["status" => "success", "code" => 3, "msg" => "提现申请提交成功"]);
            }else{
                return json(["status" => "failer", "code" => 4, "msg" => $employeeModel->getError()]);
            }
        }else{
            return json(["status" => "failer", "code" => 4, "msg" => $employeeModel->getError()]);
        }

    }

    //提现
    public function withdraw(){
        $userid = $this->request->post('user_id');
        $money = $this->request->post('money');
        if(empty($userid) || empty($money)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }
        $employeeModel = new TbEmployee();
        $result = $employeeModel->withdraw($userid,$money);
        if($result){
            return json(["status" => "success", "code" => 3, "msg" => "提现申请提交成功"]);
        }else{
            return json(["status" => "failer", "code" => 4, "msg" => $employeeModel->getError()]);
        }
    }

    //工资明细
    public function salaryDetail(){
        $userid = $this->request->post('user_id');
        $page = $this->request->post('page');
        $type = $this->request->post('type');
        if(empty($page))
            $page = 0;
        $pagesize = $this->request->post('pagesize');
        if(empty($pagesize))
            $pagesize = 20;

        if(empty($userid)|| empty($type)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }

        $employeeModel = new TbEmployee();
        $result = $employeeModel->getsalryDetail($userid,$type,$page,$pagesize);
        return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$result]);
    }

    //奋斗金明细
    public function pointsDetail(){
        $userid = $this->request->post('user_id');
        $page = $this->request->post('page');
        if(empty($page))
            $page = 0;
        $pagesize = $this->request->post('pagesize');
        if(empty($pagesize))
            $pagesize = 20;
        if(empty($userid) ){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }

        $employeeModel = new TbEmployee();
        $result = $employeeModel->getPointDetail($userid,$page,$pagesize);
        return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$result]);
    }

    //现金明细（提现明细）
    public function balanceDetail(){
        $userid = $this->request->post('user_id');
        $page = $this->request->post('page');
        if(empty($page))
            $page = 0;
        $pagesize = $this->request->post('pagesize');
        if(empty($pagesize))
            $pagesize = 20;
        if(empty($userid) ){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }

        $employeeModel = new TbEmployee();
        $result = $employeeModel->getBalanceDetail($userid,$page,$pagesize);
        return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$result]);
    }

    public function userPointConfirm(){
        $userid = $this->request->post('user_id');
        $recordid = $this->request->post('recordid');
        if(empty($userid) || empty($recordid)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }
        $employeeModel = new TbEmployee();
        $result = $employeeModel->pointUserConfirm($userid,$recordid);
        if($result){
            return json(["status" => "success", "code" => 3, "msg" => "确认成功"]);
        }else{
            $msg = $employeeModel->getError();
            if(empty($msg)){
                $msg = '确认失败';
            }
            return json(["status" => "failer", "code" => 4, "msg" => $msg]);
        }
    }


    public function singleSalaryDetail(){
        $recordid = $this->request->post('record_id');
        if(empty($recordid)){
            return json(["status" => "success", "code" => 4, "msg" => "缺少参数"]);
        }

        $saalaryModel = new Salary();
        $result = $saalaryModel->getSingleSalaryDetail($recordid);
        return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$result]);

    }


    //员工确认信息
    public function userBind(){

        $mobile = $this->request->post('mobile');
        $code = $this->request->post('code');
        $uid = $this->request->post('uid');
        $eid = $this->request->post('eid');
        if(empty($mobile) || empty($code)  || empty($uid) || empty($eid)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }
        $codeDao = new VerifyCodeDao();
        $code_data = $codeDao->getByAccount($mobile);
        if ($code_data['code'] != $code) {
            return json(["status" => "failer", "code" => 4, "msg" => "验证码错误"]);
            //return '验证码错误';
        }
        $class = new User();
        $result = $class->userBind($uid,$eid,$mobile);
        if(!$result){
            return json(["status" => "failer", "code" => 4, "msg" => $class->getError()]);
        }
        return json(["status" => "success", "code" => 3, "msg" => "绑定成功"]);
    }

    public function getEmployeeInfo(){
        $employeeid = $this->request->post('user_id');
        if(empty($employeeid)){
            $employeeid = 0;
        }

        $employeeModel = new TbEmployee();
        $result = $employeeModel->getEmployee($employeeid);
        $user = Session::get('wechatuser');
        if(!empty($user)&&is_array($user)){
            $wechatinfo['avatar'] = $user['avatar'];
            $wechatinfo['nickname'] = $user['nickname'];
        }else{
            $wechatinfo = new \stdClass();
        }
        $result['wechatuser'] = $wechatinfo;
        if($result){
            return json(["status" => "success", "code" => 3, "msg" => "",'data'=>$result]);
        }else{
            return json(["status" => "failer", "code" => 4, "msg" => "不是员工"]);
        }
    }

    public function myInfo(){
       return $this->getEmployeeInfo();
    }

    public function verifyCode(){
        $mobile = $this->request->post('mobile');
        $code = $this->request->post('code');
        if(empty($mobile) || empty($code)){
            return json(["status" => "failer", "code" => 4, "msg" => "缺少参数"]);
        }
        $codeDao = new VerifyCodeDao();
        $code_data = $codeDao->getByAccount($mobile);
        if ($code_data['code'] != $code) {
            return json(["status" => "failer", "code" => 4, "msg" => "验证码错误"]);
            //return '验证码错误';
        }else{
            return json(["status" => "success", "code" => 3, "msg" => ""]);
        }
    }

    public function newget_openid(){
        $param = input('post.');
        $validate = $this->validate($param,'user/UserValidate.get_openid');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new User();
            $result = $class->newget_openid($param);
            $result = $this->return_dao($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function employee_apply(){
        $param = input('post.');
        $validate = $this->validate($param,'user/UserValidate.employee_apply');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new EmployeeApply();
            $result = $class->add_apply($param);
            $result = $this->return_dao($result,isset($result['success_msg']) ? $result['success_msg'] : '');
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function employee_bank(){
        $param = input('post.');
        $validate = $this->validate($param,'user/UserValidate.employee_bank');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new EmployeeBank();
            $result = $class->add_bank($param);
            $result = $this->return_dao($result,$result['success_msg']);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }


}
