<?php

namespace gyo2o\model;

use gyo2o\BaseModel;
use gyo2o\dao\AccountDao;
use gyo2o\dao\AreaDao;
use gyo2o\dao\attachmentDao;
use gyo2o\dao\EmployeeApplyDao;
use gyo2o\dao\EmployeeDao;
use gyo2o\dao\UserAccountDao;
use gyo2o\dao\VerifyCodeDao;
use think\Exception;
use think\exception\PDOException;

class EmployeeApply extends BaseModel
{
    public function add_apply($param){
        $apply = new EmployeeApplyDao();
        $employee = new EmployeeDao();
        $user = new UserAccountDao();
        $user_info = $user->get_by_id($param['tb_user_id']);
        if($user_info['employee_id'] > 0){
            return "用户已存在员工身份；请勿重复提交";
        }
        try {
            $code = new VerifyCodeDao();
            $code_data = $code->getByAccount($param['contact_moblie']);
            if ($code_data['code'] != $param['code']) {
                return "验证码错误，请重新输入验证码";
            }
                $info = $employee->getByIdcard($param['idcard']);
                unset($param['code']);
                if($info){
                    if($user_info){
                        $result = $user->set_openid(['id' => $param['tb_user_id']],['employee_id' => $info['id']]);
                        $return = [
                            'success_msg' => '认证成功'
                        ];
                    }else{
                        $result = true;
                        $return = '无此用户';
                    }
                }else{
                    $result = $apply->add_apply($param);
                    $return = [
                        'success_msg' => '提交审核成功'
                    ];
                }
                if($result){
                    return $return;
                }
        }catch(PDOException $e){
            return $e->getMessage();
        }
        return '失败';
    }

    public function getApplyImg($id){
        $employeeApplyDao = new EmployeeApplyDao();
        $applyinfo = $employeeApplyDao->find($id);
        if(empty($applyinfo)){
            return '';
        }

        $attachement = new attachmentDao();
        $idcardImgPositive = $attachement->getUrlAttr($applyinfo['id_card_positive_imgid']);
        $idcardImgOpposite = $attachement->getUrlAttr($applyinfo['id_card_opposite_imgid']);
        $idcardImgHold = $attachement->getUrlAttr($applyinfo['id_card_hold_imgid']);
        return '<img src="'.$idcardImgPositive.'"><img src="'.$idcardImgOpposite.'"><img src="'.$idcardImgHold.'">';
    }

    public function backendRefuse($param){
        $employeeApplyDao = new EmployeeApplyDao();
        $applyInfo = $employeeApplyDao->find($param['ids']);
        if(empty($applyInfo)){
            $this->error = '申请记录不存在';
            return false;
        }
        $applyInfo['remark'] = $param['remark'];
        $applyInfo['site_employee_id'] = -1;
        $applyInfo['hr_employee_id'] = -1;
        return $applyInfo->save();
    }

    //审核通过
    public function backendPass($param){
        $employeeApplyDao = new EmployeeApplyDao();
        $applyInfo = $employeeApplyDao->find($param['ids']);
        if(empty($applyInfo)){
            $this->error = '申请记录不存在';
            return false;
        }
        //添加到正式员工表
        $employeeDao = new EmployeeDao();
        $areaDao = new AreaDao();
        $employeeData['d1'] = $areaDao->where(['id'=>$applyInfo['d1n']])->value('name');
        $employeeData['d2'] = $areaDao->where(['id'=>$applyInfo['d2n']])->value('name');
        $employeeData['d3'] = $areaDao->where(['id'=>$applyInfo['d3n']])->value('name');
        $employeeData['d4'] = $areaDao->where(['id'=>$applyInfo['d4n']])->value('name');
        $employeeData['d5'] = $areaDao->where(['id'=>$applyInfo['d5n']])->value('name');
        $employeeData['d1n'] = $applyInfo['d1n'];
        $employeeData['d2n'] = $applyInfo['d2n'];
        $employeeData['d3n'] = $applyInfo['d3n'];
        $employeeData['d4n'] = $applyInfo['d4n'];
        $employeeData['d5n'] = $applyInfo['d5n'];
        $employeeData['idcard'] = $applyInfo['idcard'];
        $employeeData['contact_moblie'] = $applyInfo['contact_moblie'];
        $employeeData['describe'] = $applyInfo['describe'];
        $employeeData['name'] = $applyInfo['name'];
        $employeeData['privilege_level'] = $param['level'];

        $employeeDao->startTrans();
        try{
            //员工信息是否已录入
            $accountDao = new AccountDao();
            $employeeInfo = $employeeDao->getByIdcard($applyInfo['idcard']);
            if(empty($employeeInfo)){
                $employeeid = $employeeDao->insertGetId($employeeData);
            }else{
                //已存在记录
                $accountInfo = $accountDao->where(['employee_id'=>$employeeInfo['id']])->find();
                if(!empty($accountInfo)){
                    $this->error = '该员工信息已激活不需要认证';
                    return false;
                }
                $employeeDao->where(['id'=>$employeeInfo['id']])->update($employeeData);
                $employeeid = $employeeInfo['id'];
            }


            //更新tb_user_account记录中的employee_id

            $accountDao->where(['id'=>$applyInfo['tb_user_id']])->setField('employee_id',$employeeid);

            //更新applay表
            $applyInfo['site_employee_id'] = -2;
            $applyInfo['hr_employee_id'] = -2;
            $applyInfo->save();

        }catch (PDOException $exception){
            $this->error = $exception->getMessage();
            return false;
        }

        $employeeDao->commit();
        return true;


    }

    public function getByAuthStatus($status,$offset,$limit){
        $employeeApplayDao = new EmployeeApplyDao();
        if($status == 0){
            //全部
            $where = [];
        }elseif ($status == 1){
            //待审核
            $where = ['site_employee_id'=>0,'hr_employee_id'=>0];
        }elseif ($status == 2){
            //审核未通过
            $where = ['site_employee_id'=>-1,'hr_employee_id'=>-1];
        }elseif ($status == 3){
            //审核通过
            $where = ['site_employee_id'=>-2,'hr_employee_id'=>-2];
        }

        $total = $employeeApplayDao->where($where)->count();
        $row = $employeeApplayDao->where($where)->limit($offset,$limit)->order('id','desc')->select();
        if(!empty($row)){
            $areaDao = new AreaDao();
            foreach ($row as &$value){
                $value['d1'] = $areaDao->where(['id'=>$value['d1n']])->value('name');
                $value['d2'] = $areaDao->where(['id'=>$value['d2n']])->value('name');
                $value['d3'] = $areaDao->where(['id'=>$value['d3n']])->value('name');
                $value['d4'] = $areaDao->where(['id'=>$value['d4n']])->value('name');
                $value['d5'] = $areaDao->where(['id'=>$value['d5n']])->value('name');
            }
        }
        return ['total'=>$total,'rows'=>$row];
    }

    public function importExcel($path){
        \Think\Loader::import('phpexcel.PHPExcel');

        try{
            $excel = \PHPExcel_IOFactory::load($path);
        }catch (Exception $exception){
            $this->error = '格式不正确';
            return false;
        }

        //导入部门
        $currentSheet = $excel->getSheet(0);
        set_time_limit(0);
        $allRow = $currentSheet->getHighestRow();
        #$coumn = ['B'=>'name','C'=>'d1','D'=>'d2','E'=>'d3','F'=>'d4','G'=>'d5','H'=>'describe','I'=>'site','M'=>'join_date','K'=>'idcard','L'=>'contact_moblie','N'=>'type','O'=>'bank_card','P'=>'bank_name'];
        $coumn = ['A'=>'name','B'=>'name','C'=>'name','D'=>'name','E'=>'name'];
        $data = array();
        $riderDao = new \gyo2o\dao\AreaDao();
        $riderDao->startTrans();
        $i = 0;
        for ($currentRow = 2; $currentRow <= $allRow; $currentRow ++) {
            // 循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始

            foreach ($coumn as $key=>$value){
                $data[$key]['name'] = trim($currentSheet->getCell($key.$currentRow)->getValue());// 读取到的数据，保存到数组$arr中
                if(empty($data[$key]['name'])){
                    unset($data);
                    continue;
                }
                switch ($key){
                    case 'A':
                        //一级部门,不存在则插入
                        $exist = $riderDao->where(['name'=>$data['A']['name'],'level'=>1])->find();
                        if(empty($exist)){
                            $data[$key]['id'] = $riderDao->insertGetId(['name'=>$data['A']['name'],'level'=>1,'pid'=>0]);
                        }else{
                            $data[$key]['id'] = $exist['id'];
                        }
                        unset($exist);
                        break;
                    case 'B':
                        //二级部门不存在则插入
                        $exist = $riderDao->where(['name'=>$data['B']['name'],'level'=>2,'pid'=>$data['A']['id']])->find();
                        if(empty($exist)){
                            $data[$key]['id'] = $riderDao->insertGetId(['name'=>$data['B']['name'],'level'=>2,'pid'=>$data['A']['id']]);
                        }else{
                            $data[$key]['id'] = $exist['id'];
                        }
                        unset($exist);
                        break;
                    case 'C':
                        //三级部门不存在则插入
                        $exist = $riderDao->where(['name'=>$data['C']['name'],'level'=>3,'pid'=>$data['B']['id']])->find();
                        if(empty($exist)){
                            $data[$key]['id'] = $riderDao->insertGetId(['name'=>$data['C']['name'],'level'=>3,'pid'=>$data['B']['id']]);
                        }else{
                            $data[$key]['id'] = $exist['id'];
                        }
                        unset($exist);
                        break;
                    case 'D':
                        //四级部门不存在则插入
                        $exist = $riderDao->where(['name'=>$data['D']['name'],'level'=>4,'pid'=>$data['C']['id']])->find();
                        if(empty($exist)){
                            $data[$key]['id'] = $riderDao->insertGetId(['name'=>$data['D']['name'],'level'=>4,'pid'=>$data['C']['id']]);
                        }else{
                            $data[$key]['id'] = $exist['id'];
                        }
                        unset($exist);
                        break;

                    case 'E':
                        //五级部门不存在则插入
                        $exist = $riderDao->where(['name'=>$data['E']['name'],'level'=>5,'pid'=>$data['D']['id']])->find();
                        if(empty($exist)){
                            $data[$key]['id'] = $riderDao->insertGetId(['name'=>$data['E']['name'],'level'=>5,'pid'=>$data['D']['id']]);
                        }else{
                            $data[$key]['id'] = $exist['id'];
                        }
                        unset($exist);
                        break;

                }
            }
            unset($data);
        }


        $riderDao->commit();

        return true;
    }

}