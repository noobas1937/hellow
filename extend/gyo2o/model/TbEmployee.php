<?php

namespace gyo2o\model;

use gyo2o\BaseModel;
use gyo2o\dao\CreditsIncreasementDao;
use gyo2o\dao\CreditsRecodDao;
use gyo2o\dao\CreditsRecordDao;
use gyo2o\dao\CreditsReduceDao;
use gyo2o\dao\EmployeeBankDao;
use gyo2o\dao\EmployeeDao;
use gyo2o\dao\EmployeeWithdrawDao;
use gyo2o\dao\EnterpriseDao;
use gyo2o\dao\IdentityDao;
use gyo2o\dao\SectorDao;
use gyo2o\dao\UserAccountDao;
use think\Exception;
use think\exception\PDOException;

class TbEmployee extends BaseModel
{
    public function get_list($data){
        $model = new EmployeeDao();
        $result = $this->get_base_list($data,$model,'get_list');
        $count = $this->get_base_count($data,$model,'get_count');
        if($count > 0){
            $company = new EnterpriseDao();
            $sector = new SectorDao();
            $identity = new IdentityDao();
            $luckyDraw = new LuckyDraw();
            foreach($result as $key => $val){
                $company_info = $company->get_id($val['enterprise_id']);
                $val['enterprise_id'] = $company_info['name'].'('.$company_info['id'].')';
                $sector_info = $sector->get_id($val['sector_id']);
                $val['sector_id'] = $sector_info['name'];
                $val['identity_id'] = $identity->get_value($val['identity_id']);
                $val['points'] = $luckyDraw->checkPoints($val['id']);
            }
            return ['total'=>$count,'rows'=>$result];
        }else{
            return ['total'=>0,'rows'=>[]];
        }
    }

    public function add($data){
        $model = new EmployeeDao();

        if($data){
            $user = new UserAccountDao();
            $user_info = $user->get_by_mobile($data['contact_moblie']);
            if($user_info){
                $data['tb_user_id'] = $user_info['id'];
            }
        }
        $info = $model->get_by_mobile($data['contact_moblie']);
        if($info){
            return ['msg' => '手机号已存在','code' => 0];
        }
        try{
            $result = $this->base_add($data,$model,'add');
        }catch(Exception $e){
            $result = false;
        }
        return $result;
    }

    public function edit($id,$data){
        $model = new EmployeeDao();
        $result = $this->base_edit($id,$data,$model,'edit');
        return $result;
    }

    public function del($id){
        $model = new EmployeeDao();
        $result = $this->base_del($id,$model,'del');
        return $result;
    }

    public function get_one($id){
        $model = new EmployeeDao();
        $result = $this->get_id($id,$model,'get_id');
        return $result;
    }

    //根据电话号码员工用户双向绑定
    public function bindEmployeeToUser($userAccountId){
        $employeeDao = new EmployeeDao();
        $employee = $employeeDao->getByUid($userAccountId);
        if(empty($employee)){
            return false;
        }
//        if(empty($employee['tb_user_id'])){
//            //绑定user_account_id
//            $employee->tb_user_id = $userAccountId;
//            $employee->save();
//        }
        if(!empty($employee['site'])){
            $employee['site'] = $employee['site'];
        }else if(!empty($employee['d5'])){
            $employee['site'] = $employee['d5'];
        }else if(!empty($employee['d4'])){
            $employee['site'] = $employee['d4'];
        }else if(!empty($employee['d3'])){
            $employee['site'] = $employee['d3'];
        }else if(!empty($employee['d2'])){
            $employee['site'] = $employee['d2'];
        }else if(!empty($employee['d1'])){
            $employee['site'] = $employee['d1'];
        }
        return $employee;
    }

    public function getByIdcard($idcard){
        $employeeDao = new EmployeeDao();
        $employee = $employeeDao->getByIdcard($idcard);
        if(empty($employee)){
            return false;
        }

        return $employee;
    }


    public function userCenter($userid){
        $luckyDrawModel = new LuckyDraw();
        //奋斗金
        $point = $luckyDrawModel->checkPoints($userid);
        //工资
        $creditsIncreasementDao = new CreditsIncreasementDao();
        $salary = $creditsIncreasementDao->sumSalaryByUserid($userid,1);
        //奖励
        $prize = $creditsIncreasementDao->sumSalaryByUserid($userid,2);
        //现金余额
        $widthdrawDao = new EmployeeWithdrawDao();
        $balance = $widthdrawDao->sumBalanceByUserid($userid);

        return ['point'=>$point,'salary'=>$salary,'prize'=>$prize,'balance'=>$balance];
    }

    //兑换
    public function conversion($userid,$money){
        $luckyDrawModel = new LuckyDraw();
        $point = $luckyDrawModel->checkPoints($userid);
        $datetime = date('d H:i:s');
        if(!($datetime>='10 00:00' && $datetime<='14 12:00')){
            if($money < 100){
                $this->error = '非每月10到14号12点不可提现小于100的金额，不够一百的下月才能提现';
                return false;
            }
        }

        if($point<$money){
            $this->error = '奋斗金不足';
            return false;
        }
//        $filename = RUNTIME_PATH.'/lock/'.$userid.'.lock';
//        if(!file_exists($filename)){
//            file_put_contents($filename,$userid);
//        }
//        $fp = fopen($filename,'w+');
//        if(flock($fp,LOCK_EX|LOCK_NB)){
            $creditsRecordDao = new CreditsRecodDao();
            $creditsReduceDao = new CreditsReduceDao();
            $withdrawDao = new EmployeeWithdrawDao();
            $creditsRecordDao->startTrans();
            $result1 = $creditsRecordDao->insertGetId(['credits'=>0-$money,'create_date'=>date('Y-m-d H:i:s'),'tb_employee_id'=>$userid]);
            $result2 = $creditsReduceDao->insert(['credits'=>$money,'reduce_type'=>0,'record_id'=>$result1,'employee_id'=>$userid]);
            $result3 = $withdrawDao->insert(['money'=>$money,'status'=>0,'employee_id'=>$userid,'create_time'=>date('Y-m-d H:i:s')]);
            if($result1 && $result2 && $result3){
                $point2 = $luckyDrawModel->checkPoints($userid);
                if($point2!=bcsub($point , $money,2)){
                    $this->error = '奋斗金不足'.$point2.':'.bcsub($point , $money,2);
                    $creditsRecordDao->rollback();
                    return false;
                }
                $creditsRecordDao->commit();
//                flock($fp, LOCK_UN);
                return true;
            }
            $creditsRecordDao->rollback();
            $this->error = '失败';
//            flock($fp, LOCK_UN);
        return false;
//        }else{
//            $this->error = '重复提交';
//            return false;
//        }


    }

    //提现
    public function withdraw($userid,$money){
        $employeeWithdrawDao = new EmployeeWithdrawDao();
        $balance = $employeeWithdrawDao->sumBalanceByUserid($userid);
        if($balance<$money){
            $this->error = '余额不足';
            return false;
        }
        return $employeeWithdrawDao->insert(['money'=>0-$money,'status'=>1,'employee_id'=>$userid,'create_time'=>date('Y-m-d H:i:s')]);
    }

    //工资明细
    public function getsalryDetail($userid,$type,$page,$pagesize){
        $creditsRecordDao = new CreditsRecodDao();
        //工资
        $creditsIncreasementDao = new CreditsIncreasementDao();
        $salary = $creditsIncreasementDao->sumSalaryByUserid($userid,$type);
        $record = $creditsRecordDao->alias('r')->join(['tb_credits_increasement'=>'i'],'r.id = i.record_id')->where(['r.tb_employee_id'=>$userid,'i.type'=>$type])
            ->order('create_date','desc')->limit($page*$pagesize,$pagesize)->select();
        return ['salary'=>$salary,'record'=>$record];
    }

    //奋斗金明细
    public function getPointDetail($userid,$page,$pagesize){
        $creditsRecordDao = new CreditsRecodDao();
        $drawModel = new LuckyDraw();
        $points = $drawModel->checkPoints($userid);
        $salaryModel = new Salary();
        $record = $creditsRecordDao->getByUserid($userid,$page,$pagesize);
        $employeeDao = new EmployeeDao();
        $employeeInfo = $employeeDao->find($userid);
        array_walk($record,[$salaryModel,'addtionRecord']);
        $datetime = date('d H:i:s');
        if($datetime>='10 00:00' && $datetime<='14 12:00'){
            $accounting_date = date('Y年m月').'15日';
        }else{
            $accounting_date = date('Y年m月d日',strtotime('+1day'));
        }

        if(empty($employeeInfo['bank_card'])){
            $employeeBankDao = new EmployeeBankDao();
            $bankInfo = $employeeBankDao->where(['employee_id'=>$userid])->find();
            if(!empty($bankInfo)){
                $employeeInfo['bank_card'] = $bankInfo['bank_card'];
                $employeeInfo['bank_name'] = $bankInfo['bank_name'];
            }

        }

        return ['total'=>$points,'record'=>$record,'bank_card'=>$employeeInfo['bank_card'],'bank_name'=>$employeeInfo['bank_name'],'accounting_date'=>$accounting_date,'service_mobile'=>'18062640522'];
    }

    //现金明细
    public function getBalancedetail($userid,$page,$pagesize){
        $withdrawDao = new EmployeeWithdrawDao();
        $increasementDao = new CreditsIncreasementDao();
        $record =  $withdrawDao->withdrawRecord($userid,$page,$pagesize);
        $balance = $withdrawDao->sumBalanceByUserid($userid);
        $unfreeze = $increasementDao->sumFreezenByUserid($userid);
        return ['record'=>$record,'balance'=>$balance,'unfreeze'=>$unfreeze];
    }

    //员工工资确认
    public function pointUserConfirm($userid,$recordid){
        $creditsIncreasementDao = new CreditsIncreasementDao();
        $increasement = $creditsIncreasementDao->where(['record_id'=>$recordid])->find();
        if(empty($increasement) || $increasement['credits']<0){
            $this->error = '负数工资不需要确认';
            return false;
        }
        return $creditsIncreasementDao->userConfirm($userid,$recordid);
    }

    public function countSalary($userid,$type){
        $creditsIncreasementDao = new CreditsIncreasementDao();
        return $creditsIncreasementDao->where(['type'=>$type,'employee_id'=>$userid])->sum('credits');
    }

    //导入员工excel表格
    public function importExcel($path,$coumn=['A'=>'name','B'=>'type','C'=>'describe','D'=>'site','E'=>'join_date','F'=>'idcard','G'=>'bank_card','H'=>'bank_name','I'=>'contact_moblie','D'=>'d5']){
        \Think\Loader::import('phpexcel.PHPExcel');

        try{
            $excel = \PHPExcel_IOFactory::load($path);
        }catch (Exception $exception){
            $this->error = '格式不正确';
            return false;
        }

        $count = $excel->getSheetCount();
//        if($count == 4){
//            //导入场内可中奖名单
//            $currentSheet = $excel->getSheet(1);
//            $resultallinnerWinning = $this->importInnerWinning($currentSheet);
//            if(!$resultallinnerWinning){
//                return false;
//            }
//
//            //导入场外可中奖名单
//            $currentSheet = $excel->getSheet(3);
//            $resultallouterWinning = $this->importInnerWinning($currentSheet,2);
//            if(!$resultallouterWinning){
//                return false;
//            }
//
//            //导入场内员工
//            $currentSheet = $excel->getSheet(0);
//            $resultinner = $this->setInnerUser($currentSheet);
//            if(!$resultinner){
//                return false;
//            }
//        }else{
            //导入全体员工
            $currentSheet = $excel->getSheet(0);
            $resultalluser = $this->imporetAllUser($currentSheet,$coumn);
            if(!$resultalluser){
                return false;
            }
//        }



         return true;

    }

    public function imporetAllUser($currentSheet,$coumn){
        set_time_limit(0);
        $allRow = $currentSheet->getHighestRow();
        #$coumn = ['B'=>'name','C'=>'d1','D'=>'d2','E'=>'d3','F'=>'d4','G'=>'d5','H'=>'describe','I'=>'site','M'=>'join_date','K'=>'idcard','L'=>'contact_moblie','N'=>'type','O'=>'bank_card','P'=>'bank_name'];
//        $coumn = ['A'=>'name','B'=>'type','C'=>'describe','D'=>'site','E'=>'join_date','F'=>'idcard','G'=>'bank_card','H'=>'bank_name','I'=>'contact_moblie','D'=>'d5'];
        $data = array();
        $riderDao = new \gyo2o\dao\EmployeeDao();
        $riderDao->startTrans();
        $i = 0;
        for ($currentRow = 2; $currentRow <= $allRow; $currentRow ++) {
            // 循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始

            foreach ($coumn as $key=>$value){
                $data[$i][$value] = trim($currentSheet->getCell($key.$currentRow)->getValue());// 读取到的数据，保存到数组$arr中
                if($value=='join_date'){
                    $data[$i][$value] = excelTime($data[$i][$value]);
                }

                if($value == 'sex'){
                    if($data[$i][$value]=='男'){
                        $data[$i][$value] = 1;
                    }elseif($data[$i][$value] == '女'){
                        $data[$i][$value] = 2;
                    }else{
                        $data[$i][$value] = 0;
                    }
                }
                if($value=='idcard'){
                    if(!preg_match('/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{2}[0-9Xx]$)/',$data[$i][$value])){
                        $this->error = $currentSheet->getCell('A'.$currentRow).': '.$data[$i][$value].'身份证号码有误';
                        return false;
                    }
                }




            }
            $data[$i]['create_date'] = date('Y-m-d H:i:s');
            $employee = $riderDao->getByIdcard($data[$i]['idcard']);
            if(empty($employee)){
                //新增
                try{
                    $riderDao->insert($data[$i]);
                }catch (PDOException $e){
                    $this->error = $e->getMessage();
                    $riderDao->rollback();
                    return false;
                }
            }else{
                //修改
                try{
                    $data[$i]['update_date'] = date('Y-m-d H:i:s');
                    unset($data[$i]['create_date']);
                    $employee->save($data[$i]);
                }catch (PDOException $e){
                    $riderDao->rollback();
                    $this->error = $e->getMessage();
                    return false;
                }
            }
            unset($data[$i]);
            $i++;
        }


        $riderDao->commit();

        return true;
    }

    public function importInnerWinning($currentSheet,$type = 1){

        $allRow = $currentSheet->getHighestRow();
        if($type == 1){
            $coumn = ['I'=>'mobile'];
        }else{
            $coumn = ['M'=>'mobile'];
        }

        $data = array();

        $i = 0;
        for ($currentRow = 2; $currentRow <= $allRow; $currentRow ++) {
            // 循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始

            foreach ($coumn as $key=>$value){
                $data[$i][$value] = trim($currentSheet->getCell($key.$currentRow)->getValue());// 读取到的数据，保存到数组$arr中

            }
            $i++;
        }
        if($type == 1){
            $riderDao = new \gyo2o\dao\EmployeeInnerWinningDao();
        }else{
            $riderDao = new \gyo2o\dao\EmployeeOuterWinningDao();
        }

        try{
            $result = $riderDao->saveAll($data,false);
        }catch (PDOException $exception){
            $this->error = $exception->getMessage();
            return false;
        }

        return $result;
    }

    protected function setInnerUser($currentSheet){
        $allRow = $currentSheet->getHighestRow();
        $employee = new EmployeeDao();
        $data = array();

        for ($currentRow = 2; $currentRow <= $allRow; $currentRow ++) {
            // 循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
            $mobile = trim($currentSheet->getCell('F'.$currentRow)->getValue());// 读取到的数据，保存到数组$arr中
            if(empty($mobile)){
                continue;
            }
            $employeeid = $employee->get_by_mobile($mobile);
            if(empty($employeeid)){
                continue;
            }
            $data[] = ['employee_id'=>$employeeid['id']];
        }

        $riderDao = new \gyo2o\dao\EmployeeInnerDao();


        try{
            $result = $riderDao->saveAll($data,false);
        }catch (PDOException $exception){
            $this->error = $exception->getMessage();
            return false;
        }

        return $result;
    }

    public function unbind($employeeid){
        $employeeDao = new EmployeeDao();
        return $employeeDao->where(['id'=>$employeeid])->setField('tb_user_id',NULL);
    }


    public function getEmployee($employeeid){
        $employeeDao = new EmployeeDao();
        $employee = $employeeDao->find($employeeid);
        if(empty($employee)){
            return [];
        }
//        if(empty($employee['tb_user_id'])){
//            //绑定user_account_id
//            $employee->tb_user_id = $userAccountId;
//            $employee->save();
//        }
        if(!empty($employee['site'])){
            $employee['site'] = $employee['site'];
        }else if(!empty($employee['d5'])){
            $employee['site'] = $employee['d5'];
        }else if(!empty($employee['d4'])){
            $employee['site'] = $employee['d4'];
        }else if(!empty($employee['d3'])){
            $employee['site'] = $employee['d3'];
        }else if(!empty($employee['d2'])){
            $employee['site'] = $employee['d2'];
        }else if(!empty($employee['d1'])){
            $employee['site'] = $employee['d1'];
        }
        if(empty($employeeInfo['bank_card'])){
            $employeeBankDao = new EmployeeBankDao();
            $bankInfo = $employeeBankDao->where(['employee_id'=>$employeeid])->find();
            if(!empty($bankInfo)){
                $employeeInfo['bank_card'] = $bankInfo['bank_card'];
                $employeeInfo['bank_name'] = $bankInfo['bank_name'];
            }

        }
        return $employee;
    }
}
