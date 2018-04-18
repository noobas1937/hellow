<?php

namespace app\admin\controller\enterprise;

use app\common\controller\Backend;

use gyo2o\dao\VerifyCodeDao;
use gyo2o\model\TbRecord;
use gyo2o\model\VerifyCode;
use telephone\SMS;
use think\Controller;
use think\Request;

/**
 * 积分纪录管理
 *
 * @icon fa fa-circle-o
 */
class Record extends Backend
{
    

    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    public function index($ids = null)
    {
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $salaryModel = new \gyo2o\model\Salary();

            $result = $salaryModel->getSalaryWaste($where, $sort, $order, $offset, $limit,$ids);

            return json($result);
        }
        $this->view->assign('ids',$ids);
        return $this->view->fetch();
    }

    public function add($ids = null){
        $mobile = session('admin.mobile');

        if ($this->request->isPost())
        {
            $enterprise = new TbRecord();
            $params = $this->request->post("row/a");
            if(!$mobile){
                $this->error('管理员请绑定手机号');exit;
            }
            $verify = new VerifyCode();
            $code_data = $verify->get_mobile($mobile);

            if(isset($params['code']) && $params['code'] == $code_data['code']){
                $verify->set_status($code_data['id'],1);
                $code_data = $verify->get_mobile($mobile);
            }else{
                return json(['code' => '100001','msg' => '验证码错误']);
            }

            if($code_data && (date('Y-m-d 00:00:00',strtotime($code_data['create_date'])) != date('Y-m-d 00:00:00') || $code_data['is_status'] != 1)){
                return json(['code' => '100001','msg' => '验证码已过期或未通过验证']);
            }

            $params['tb_employee_id'] = $ids;
            $validate = $this->validate($params,'admin/TbEmployee.add_record');
            if($validate === true){
                unset($params['code']);
                $result = $enterprise->add($params);
                $this->result_return($result);
            }else{
                $this->error($validate);
            }

        }

        if(!$mobile){
            $this->error('管理员请绑定手机号');exit;
        }
        $verify = new VerifyCode();
        $code_data = $verify->get_mobile($mobile);
        $code_sms = 0;
        if(!$code_data){
            $code_sms = 1;
        }elseif($code_data && ($code_data['is_status'] != 1 || date('Y-m-d 00:00:00',strtotime($code_data['create_date'])) != date('Y-m-d 00:00:00'))){
            $code_sms = 1;
        }

        $this->view->assign('code_sms',$code_sms);
        $this->view->assign('ids',$ids);
        return $this->view->fetch();
    }

    public function get_code(){
        $mobile = input('post.mobile');
        if($mobile != session('admin.mobile')){
            $this->error('手机号错误，请输入绑定手机号');
        }
        $rand_vcode = rand(99999, 1000000);
        $msg = '【踢踢果园】您的验证码是' . $rand_vcode . '！';
        $sms = new SMS();
        $csms = \think\Config::get('sms');
        $sms->sprdid = $csms['SMS_PRODUCT_ID'];
        $send = $sms->send($msg, $mobile);
        if ($send && $rand_vcode) {
            $verify = new VerifyCodeDao();
            $code_data['account'] = $mobile;
            $code_data['code'] = $rand_vcode;
            $code_data['create_date'] = date("Y-m-d H:i:s");
            $verify->insert($code_data);
            $this->success('发送成功');
        } else {
            $this->error('发送失败');
        }
    }

}
