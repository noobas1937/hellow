<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/12 0012
 * Time: 下午 7:51
 */

namespace gyo2o\model;


use gyo2o\BaseModel;
use gyo2o\dao\CreditsIncreasementDao;
use gyo2o\dao\CreditsRecordDao;
use gyo2o\dao\CreditsReduceDao;
use gyo2o\dao\EmployeeDao;
use gyo2o\dao\EmployeeWithdrawDao;
use think\Db;
use think\exception\PDOException;

class OutputSalary extends BaseModel
{
    public function outPutExcel($year,$month,$stime,$etime){
        set_time_limit(0);
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:filename=".$year.'-'.$month.'_'.$stime.'-'.$etime.".xls");
        $month++;
        if($month == 13){
            $year++;
            $month = 1;
        }
        if($month < 10){
            $month = '0'.$month;
        }
        $yearmonth = $year.'-'.$month;
        $employeeDao = new EmployeeDao();
        $data = "编号\t一级部门\t二级部门\t三级部门\t四级部门\t五级部门\t姓名\t电话\t身份证\t年\t月\t核发\t实发\r";
        $i = 1;
        $employees = $employeeDao->select();
        foreach ($employees as $employee){
            $sql1 = "select sum(ci.credits) as sum from tb_credits_increasement ci INNER JOIN tb_credits_record cr on ci.record_id=cr.id 
where ci.employee_id=$employee[id] AND DATE_FORMAT(create_date,'%Y-%m') = '$yearmonth'";
            $salaredetail = Db::query($sql1);
            if(empty($salaredetail[0]['sum'])){
                continue;
            }
            $sql2 = "select sum(cr.credits) as sum from tb_credits_reduce ci INNER JOIN tb_credits_record cr on ci.record_id=cr.id 
where ci.employee_id=$employee[id] AND ci.reduce_type = 0 AND create_date > '$stime' AND create_date <= '$etime'";
            $withdraw = Db::query($sql2);

            $data.=$i."\t";
            $data.=$employee['d1']."\t";
            $data.=$employee['d2']."\t";
            $data.=$employee['d3']."\t";
            $data.=$employee['d4']."\t";
            $data.=$employee['d5']."\t";
            $data.=$employee['name']."\t";
            $data.=$employee['contact_moblie']."\t";
            $idcard = $employee['idcard'];
            $data.="'".$idcard."\t";
            $data.=$year."\t";
            $data.=($month-1)."\t";
            $salary = $salaredetail[0]['sum']? $salaredetail[0]['sum']:0;
            $data.=  round($salary,2)."\t";
            $draw =  abs($withdraw[0]['sum']?$withdraw[0]['sum']:0);
            $data.=  round($draw,2)."\r";
            $i++;
        }
        $strexport=iconv('UTF-8',"GB2312//IGNORE",$data);
        exit($strexport);


    }

    public function autoconvert($year,$month){

        set_time_limit(0);
        $creditsIncreasementDao = new CreditsIncreasementDao();
        $creditsRecordDao = new CreditsRecordDao();
        $withdrawDao = new EmployeeWithdrawDao();
        $creditesRecduce = new CreditsReduceDao();
        $creditsIncreasementDao->startTrans();
        //确认过的从未兑换的兑换提现
        $month++;
        if($month == 13){
            $year++;
            $month = 1;
        }
        if($month < 10){
            $month = '0'.$month;
        }
        $yearmonth = $year.'-'.$month;
        $sql = "select * from tb_credits_increasement ci INNER JOIN tb_credits_record cr on ci.record_id=cr.id 
where isconfirm = 2 AND DATE_FORMAT(create_date,'%Y-%m') = '$yearmonth'";
        $increasementconfirms = Db::query($sql);
        foreach ($increasementconfirms as $confirm){
            $sql2 = "select * from tb_credits_reduce ci INNER JOIN tb_credits_record cr on ci.record_id=cr.id 
where ci.employee_id = $confirm[employee_id] AND DATE_FORMAT(create_date,'%Y-%m') = '$yearmonth'";
            $result = Db::query($sql2);
            if(count($result) == 0){
                //兑换
                $result1 = $creditsRecordDao->insertGetId(['credits'=>0-$confirm['credits'],'create_date'=>date('Y-m-d H:i:s'),'tb_employee_id'=>$confirm['employee_id']]);
                $creditesRecduce->insert(['credits'=>$confirm['credits'],'reduce_type'=>0,'record_id'=>$result1,'employee_id'=>$confirm['employee_id']]);
                $withdrawDao->insert(['money'=>$confirm['credits'],'employee_id'=>$confirm['employee_id'],'create_time'=>date('Y-m-d')]);
                //提现
                $withdrawDao->insert(['money'=>0 - $confirm['credits'],'employee_id'=>$confirm['employee_id'],'status'=>1,'create_time'=>date('Y-m-d')]);
            }
        }

        //未确认的直接确认，兑换提现
        $increasements = $creditsIncreasementDao->where(['isconfirm'=>0])->select();
        foreach ($increasements as $increasement){

            try{
                //确认
                $increasement->save(['isconfirm'=>2]);
                if($increasement['credits']>0){
                    //兑换
                    $result1 = $creditsRecordDao->insertGetId(['credits'=>0-$increasement['credits'],'create_date'=>date('Y-m-d H:i:s'),'tb_employee_id'=>$increasement['employee_id']]);
                    $creditesRecduce->insert(['credits'=>$increasement['credits'],'reduce_type'=>0,'record_id'=>$result1,'employee_id'=>$increasement['employee_id']]);
                    $withdrawDao->insert(['money'=>$increasement['credits'],'employee_id'=>$increasement['employee_id'],'create_time'=>date('Y-m-d H:i:s')]);
                    //提现
                    $withdrawDao->insert(['money'=>0 - $increasement['credits'],'employee_id'=>$increasement['employee_id'],'status'=>1,'create_time'=>date('Y-m-d H:i:s')]);
                }

            }catch (PDOException $exception){
                $this->error = $exception->getMessage();
                $creditsIncreasementDao->rollback();
                return false;
            }

        }

        $creditsIncreasementDao->commit();
        return true;
    }

    public function autoconvert2($year,$month){
        set_time_limit(0);
        $month++;
        if($month == 13){
            $year++;
            $month = 1;
        }
        if($month < 10){
            $month = '0'.$month;
        }
        $yearmonth = $year.'-'.$month;

        $employeeDao = new EmployeeDao();
        $creditsIncreasementDao = new CreditsIncreasementDao();
        $creditsRecordDao = new CreditsRecordDao();
        $withdrawDao = new EmployeeWithdrawDao();
        $creditesRecduce = new CreditsReduceDao();
        //未激活用户自动确认工资并兑换提现
        $employees = $employeeDao->where(['tb_user_id'=>array('exp','IS NULL')])->select();
        $luckyDraw = new LuckyDraw();
        $employeeDao->startTrans();
        foreach ($employees as $user){
            try{
                //工资确认
                $creditsIncreasementDao->where(['employee_id'=>$user['id']])->setField('isconfirm',2);
                //奋斗金余额提现
                $salary = $luckyDraw->checkPoints($user['id']);
                if($salary > 0){
                    //兑换
                    $result1 = $creditsRecordDao->insertGetId(['credits'=>0-$salary,'create_date'=>date('Y-m-d H:i:s'),'tb_employee_id'=>$user['id']]);
                    $creditesRecduce->insert(['credits'=>$salary,'reduce_type'=>0,'record_id'=>$result1,'employee_id'=>$user['id']]);
                    $withdrawDao->insert(['money'=>$salary,'employee_id'=>$user['id'],'create_time'=>date('Y-m-d H:i:s')]);
                    //提现
                    $withdrawDao->insert(['money'=>0 - $salary,'employee_id'=>$user['id'],'status'=>1,'create_time'=>date('Y-m-d H:i:s')]);
                }
            }catch (PDOException $exception){
                $this->error = $exception->getMessage();
                $employeeDao->rollback();
                return false;
            }
            unset($salary);
        }
        $employeeDao->commit();
        return true;
    }

}