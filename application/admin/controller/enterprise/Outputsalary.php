<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/12 0012
 * Time: 下午 7:47
 */

namespace app\admin\controller\enterprise;


use app\common\controller\Backend;

class Outputsalary extends  Backend
{
    public function add(){
        if ($this->request->isPost())
        {
            $year = $this->request->post('year');
            $month = $this->request->post(('month'));
            //提现开始，截止时间
            $stime = $this->request->post('sday');
            $etime = $this->request->post('eday');
            if(empty($year)||empty($month)||empty($stime)||empty($etime)){
                $this->error('请选择年月和提现起止时间');
            }
            if($year>date('Y') || ($year== date('Y') && $month>date('m'))){
                $this->error('工资年月选择有误');
            }

            $sday = date('Y-m',strtotime($stime));
            $eday = date('Y-m',strtotime($etime));
            $monthplus = $this->monthaddone($month,$year);
            if($sday!=$eday&&$sday!=$monthplus){
                $this->error('提现起止时间选择有误');
            }


            $output = new \gyo2o\model\OutputSalary();
            $output->outPutExcel($year,$month,$stime,$etime);



        }
        return $this->view->fetch();
    }

    private function monthaddone($month,$year){
        $month++;
        if($month == 13){
            $year++;
            $month = 1;
        }
        if($month < 10){
            $month = '0'.$month;
        }

        return $year.'-'.$month;
    }


}