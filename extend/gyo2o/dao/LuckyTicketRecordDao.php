<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/25 0025
 * Time: 下午 2:31
 */

namespace gyo2o\dao;


use think\Model;

class LuckyTicketRecordDao extends Model
{
    protected $table = 'tb_lucky_ticket_record';
    protected $append = ['ticket_name','employee_name'];

    public function getTicketnameAttr($value,$row){
        $luckyTicketDao = new LuckyTicketDao();
        $ticket = $luckyTicketDao->find($row['ticket_id']);
        return $ticket['name'];
    }

    public function getEmployeenameAttr($value,$row){
        $employeeDao = new EmployeeDao();
        $employee = $employeeDao->find($row['employee_id']);
        return $employee['name'];
    }

    public function getTotal($where,$ticketid){
        return $this->where($where)->where(['ticket_id'=>$ticketid])->count();
    }

    public function getTickRecord($where, $sort, $order, $offset, $limit,$tickeid){
        return $this->where($where)->where(['ticket_id'=>$tickeid])->order($sort,$order)->limit($offset,$limit)->select();
    }

    public function getByEmployeeAndTicketids($userid,$luckyTicketids){
        return $this->where(['status'=>0,'employee_id'=>$userid,'ticket_id'=>['in',$luckyTicketids]])->find();
    }

    public function getByuserid($userid){
        return $this->where(['employee_id'=>$userid])->select();
    }

}