<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/25 0025
 * Time: 下午 3:33
 */

namespace gyo2o\model;


use gyo2o\BaseModel;
use gyo2o\dao\EmployeeDao;
use gyo2o\dao\LuckyDrawDao;
use gyo2o\dao\LuckyTicketDao;
use gyo2o\dao\LuckyTicketRecordDao;

class LuckyTicketRecordModel extends BaseModel
{
    public function getRecord($where, $sort, $order, $offset, $limit,$ticketid){
        $ticketRecordDao = new LuckyTicketRecordDao();
        $total = $ticketRecordDao->getTotal($where,$ticketid);
        if(empty($total)){
            return ['rows'=>[],'total'=>0];
        }
        $result = $ticketRecordDao->getTickRecord($where, $sort, $order, $offset, $limit,$ticketid);
        return ['total'=>$total,'rows'=>$result];
    }

    public function addByEmployee($ticketid,$employid){
        $luckyTicketRecordDao = new LuckyTicketRecordDao();
        return $luckyTicketRecordDao->insert(['ticket_id'=>$ticketid,'employee_id'=>$employid,'create_time'=>date('Y-m-d H:i:s')]);
    }

    public function addBySector($ticketid,$enterpriseid,$sectorid){
        $ticketRecordDao = new LuckyTicketRecordDao();
        $employeeDao = new EmployeeDao();
        $employeeids = $employeeDao->getBySector($enterpriseid,$sectorid);
        if(empty($employeeids)){
            return false;
        }
        $insertData = array();
        foreach($employeeids as $valaue){
            $insertData[] = ['create_time'=>date('Y-m-d H:i:s'),'employee_id'=>$valaue,'ticket_id'=>$ticketid];
        }
        return $ticketRecordDao->insertAll($insertData);

    }

    public function getByUserid($userid,$drawid){
        $ticketRecordDao = new LuckyTicketRecordDao();
        $ticket = $ticketRecordDao->alias('r')->join(['tb_lucky_ticket'=>'t'],'t.id = r.ticket_id')
        ->where(['employee_id'=>$userid,'t.activity_id'=>$drawid,'status'=>0])->select();
        if(empty($ticket)){
            return ['total'=>0,'tickets'=>[]];
        }

        $startday = date('d',strtotime($ticket[0]['start_time']));

        return ['total'=>count($ticket),'tickets'=>$ticket,'day'=>$startday];
    }

    public function addTicketinfo(&$row){
        $ticketDao = model('\\gyo2o\\dao\\LuckyTicketDao');
        $ticketInfo = $ticketDao->find($row['ticket_id']);
        $row['ticket_info'] = $ticketInfo;
    }

    //发放五张年会抽奖券
    public function fiveTicket($userid,$drawid){
        $ticketDao = new LuckyTicketDao();
        $drawDao = new LuckyDrawDao();
        $draw = $drawDao->find($drawid);

        //是否已设置抽奖券
        $ticket = $ticketDao->getByActivityOnly($drawid);
        if(empty($ticket)){
            //创建抽奖券
            $ticketid = $ticketDao->insertGetId(['name'=>'年会抽奖券','start_time'=>$draw['start_date'],'end_time'=>$draw['end_date'],'type'=>2,'activity_id'=>$drawid]);
        }else{
            $ticketid = $ticket[0]['id'];
        }

        //判断是否已经发放了5张抽奖券
        $number = $ticketDao->alias('t')->join(['tb_lucky_ticket_record'=>'r'],'t.id = r.ticket_id')
            ->where(['t.activity_id'=>$drawid,'employee_id'=>$userid])->count();
        if($number<5){
            //未发则发放5张
            $data = [];
            for($i=0;$i<5;$i++){
                $data[] = ['ticket_id'=>$ticketid,'employee_id'=>$userid,'create_time'=>date('Y-m-d H:i:s')];
            }
            $ticketRecordDao = new LuckyTicketRecordDao();
            $ticketRecordDao->insertAll($data);
        }
    }
}