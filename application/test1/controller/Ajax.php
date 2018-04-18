<?php
namespace app\test1\controller;

use gyo2o\wechat\JsApiPay;
use think\Db;

class Ajax
{
    public function index(){

    }

    public function test(){
        //查找全部员工
        set_time_limit(0);
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:filename=流水.xls");
        $data = "编号\t一级部门\t二级部门\t三级部门\t四级部门\t五级部门\t姓名\t电话\t身份证\t工资收入\t奖金收入\t中奖收入\t已消费\t夺宝关闭退还\t提现\t未确认工资\r";
        $sqlstr = 'select * from tb_employee';
        $employees= Db::query($sqlstr);
        $i = 1;
        foreach ($employees as $employee){
            //工资收入
            $sqlstr  = 'select sum(credits) as total from tb_credits_increasement where type=1 AND grand_type = 0 AND employee_id ='.$employee['id'];
            $a = Db::query($sqlstr);
            $a = $a[0]['total']? $a[0]['total']:0;
            //奖金总收入
            $sqlstr = 'select sum(credits) as total from tb_credits_increasement where type = 2 AND grand_type = 0 AND employee_id ='.$employee['id'];
            $b = Db::query($sqlstr);
            $b = $b[0]['total']? $b[0]['total']:0;
            //中奖收入
            $sqlstr = 'select sum(credits) as total from tb_credits_increasement where type = 2 AND grand_type IN (51,52,53,54) AND employee_id ='.$employee['id'];
            $c = Db::query($sqlstr);
            $c = $c[0]['total']? $c[0]['total']:0;

            //已消费
            $sqlstr = 'select sum(credits) as total from tb_credits_reduce where reduce_type>0 AND employee_id ='.$employee['id'];
            $d = Db::query($sqlstr);
            $d = $d[0]['total']? $d[0]['total']:0;
            //已提现
            $sqlstr = 'select sum(credits) as total from tb_credits_reduce where reduce_type = 0 AND employee_id ='.$employee['id'];
            $e= Db::query($sqlstr);
            $e = $e[0]['total']? $e[0]['total']:0;
            //夺宝开奖失败退还
            $sqlstr = 'select sum(credits) as total from tb_credits_increasement where type = 2 AND grand_type>0 AND grand_type NOT IN (51,52,53,54) AND employee_id ='.$employee['id'];
            $f = Db::query($sqlstr);
            $f = $f[0]['total']? $f[0]['total']:0;
            //未确认工资
            $sqlstr = 'select sum(credits) as total from tb_credits_increasement where type = 1 AND isconfirm=0 AND employee_id ='.$employee['id'];
            $g = Db::query($sqlstr);
            $g = $g[0]['total']? $g[0]['total']:0;
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
            $data.= 100*round($a,2)."\t";
            $data.= 100*round($b,2)."\t";
            $data.= 100*round($c,2)."\t";
            $data.= 100*round($d,2)."\t";
            $data.= 100*round($f,2)."\t";
            $data.= 100*round($e,2)."\t";
            $data.= 100*round($g,2)."\r";
            $i++;
        }
        $strexport=iconv('UTF-8',"GB2312//IGNORE",$data);
        exit($strexport);
    }
}
