<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/25 0025
 * Time: 上午 10:07
 */

namespace gyo2o\dao;


use think\Model;

class LuckyTicketDao extends Model
{
    protected $table = 'tb_lucky_ticket';
    protected $append = ['activity_name'];

    private $ticketType = ['1'=>'通用','2'=>'指定活动专用'];

    public function getTicketType(){
        return $this->ticketType;
    }

    public function getActivitynameAttr($value,$row){
        if(empty($row['activity_id'])){
            return null;
        }

        $luckyDrawDao = new LuckyDrawDao();
        $activity = $luckyDrawDao->getById($row['activity_id']);
        return $activity['title'];
    }

    public function setActivityidAttr($value,$row){
        if($row['type'] == 1){
            return 0;
        }else{
            return $value;
        }
    }

    public function getByActivity($activityid){
        return $this->where(['activity_id'=>$activityid,'start_time'=>['<',date('Y-m-d H:i:s')],'end_time'=>['>',date('Y-m-d H:i:s')]])->select();
    }

    public function getByType(){
        return $this->where(['type'=>1,'start_time'=>['<',date('Y-m-d H:i:s')],'end_time'=>['>',date('Y-m-d H:i:s')]])->select();
    }

    public function getByActivityOnly($activityid){
        return $this->where(['activity_id'=>$activityid])->select();
    }
}