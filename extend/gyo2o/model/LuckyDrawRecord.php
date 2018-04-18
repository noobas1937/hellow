<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27 0027
 * Time: 下午 1:45

 */

namespace gyo2o\model;



use gyo2o\BaseModel;
use gyo2o\dao\EmployeeDao;
use gyo2o\dao\LuckyDrawAwardDao;
use gyo2o\dao\LuckyDrawDao;

use gyo2o\dao\LuckyDrawRecordDao;

class LuckyDrawRecord extends BaseModel
{

    public function gerAwardRecord($where, $sort, $order, $offset, $limit,$id){
        $luckyDrawRecord = new LuckyDrawRecordDao();
        $total = $luckyDrawRecord->where($where)->where('award_id',$id)->count();
        if($total){
            $rows = $luckyDrawRecord->getByAwardId($where, $sort, $order, $offset, $limit,$id);
            array_walk($rows,[$this,'processRow']);


            $tb_lucky_draw_award = new LuckyDrawAwardDao();
            foreach($rows as $key => $val){
                if($val['award_id'] == -1){
                    $val['is_receive'] = '未开奖';
                    $val['award_id'] = '未开奖';
                }else{
                    $lucky_draw_award_info = $tb_lucky_draw_award->getByAwardId($val['award_id']);
                    $val['award_id'] = $lucky_draw_award_info['name'];
                    if($val['is_receive'] == 1){
                        $val['is_receive'] = '已领取';
                    }else{
                        $val['is_receive'] = '未领取';
                    }
                }

            }
            return ['total'=>$total,'rows'=>$rows];
        }

        return ['total'=>0,'rows'=>[]];

    }

    public function get_one($id){
        $model = new LuckyDrawRecordDao();
        $result = $this->get_id($id,$model,'get_id');
        $LuckyDraw = new LuckyDrawAwardDao();
        if($result){
            $result['lucky_draw'] = $LuckyDraw->getByAwardId($result['award_id']);
        }
        return $result;
    }

    public function edit($id,$data){
        $model = new LuckyDrawRecordDao();
        $result = $this->base_edit($id,$data,$model,'edit');
        return $result;
    }

    protected function processRow(&$row){
        $employeeDao = new EmployeeDao();
        $employee = $employeeDao->get_id($row['employee_id']);
        $row['employee_name'] = $employee['name'];
    }

    public function get_list($data,$ids=null){
        $model = new LuckyDrawRecordDao();

        $result = $this->get_base_list($data,$model,'get_list',$ids);
        $count = $this->get_base_count($data,$model,'get_count');
        if($count > 0){
            $tb_lucky_draw = new LuckyDrawDao();
            $tb_lucky_draw_award = new LuckyDrawAwardDao();
            foreach($result as $key => $val){
                $lucky_draw_info = $tb_lucky_draw->getById($val['lucky_draw_id']);
                $val['lucky_draw_id'] = $lucky_draw_info['title'];
                if($val['award_id'] == -1){
                    $val['is_receive'] = '未开奖';
                    $val['award_id'] = '未开奖';
                }else{
                    $lucky_draw_award_info = $tb_lucky_draw_award->getByAwardId($val['award_id']);
                    $val['award_id'] = $lucky_draw_award_info['name'];
                    if($val['is_receive'] == 1){
                        $val['is_receive'] = '已领取';
                    }else{
                        $val['is_receive'] = '未领取';
                    }
                }
            }
            return ['total'=>$count,'rows'=>$result];
        }else{
            return ['total'=>0,'rows'=>[]];
        }
    }
    public function getRecoud($employeeid,$drawid){
        $luckyDrawRecordDao = new LuckyDrawRecordDao();
        $reslut = $luckyDrawRecordDao->getByEmployIdAndDrawId($employeeid,$drawid);
        return $reslut;
    }

    public function getUserRecoud($employeeid,$drawid){
        $luckyDrawRecordDao = new LuckyDrawRecordDao();
        $reslut = $luckyDrawRecordDao->getByEmployIdAndDrawId($employeeid,$drawid);
        array_walk($reslut,[$this,'addtionAwardName']);
        return $reslut;
    }

    public function addtionAwardName(&$row){
        $luckyDrawAwardDao = new LuckyDrawAwardDao();
        if($row['award_id']){
            $award = $luckyDrawAwardDao->getByAwardId($row['award_id']);
            $row['award_name'] = $award['name'];
            $row['award_img'] = $award['img_id'];
            $row['type'] = $award['type'];
        }else{
            $row['award_name'] = '谢谢参与';
            $row['type'] = 1;
        }
        $employeeDao = new EmployeeDao();
        $employee = $employeeDao->get_by_id($row['employee_id']);
        if($employee){
            $row['employee_name'] = $employee['name'];
            $row['mobile'] = $employee['contact_moblie'];
            if(!empty($employee['site'])){
                $row['site'] = $employee['site'];
            }else if(!empty($employee['d5'])){
                $row['site'] = $employee['d5'];
            }else if(!empty($employee['d4'])){
                $row['site'] = $employee['d4'];
            }else if(!empty($employee['d3'])){
                $row['site'] = $employee['d3'];
            }else if(!empty($employee['d2'])){
                $row['site'] = $employee['d2'];
            }else if(!empty($employee['d1'])){
                $row['site'] = $employee['d1'];
            }

        }
        if(!empty($row['create_date'])){
            $timestemp = strtotime($row['create_date']);
            $row['minute'] =floor(((time()-$timestemp)%3600)/60);
            $row['hour'] = floor(((time()-$timestemp)/3600))%24;
            $row['day'] = floor(floor((time()-$timestemp)/3600)/24);
        }



    }

    //活动参与纪录
    public function getLuckyDrawRecord($where, $sort, $order, $offset, $limit,$drawid){
        $luckyDrawRecordDao = new LuckyDrawRecordDao();
        $total = $luckyDrawRecordDao->where($where)->where('lucky_draw_id',$drawid)->count();
        if($total){
            $result = $luckyDrawRecordDao->getByDrawidBackend($where,$sort,$order,$offset,$limit,$drawid);
            array_walk($result,[$this,'processRow']);
            return ['total'=>$total,'rows'=>$result];
        }

        return ['total'=>0,'rows'=>[]];

    }

    //用户抽奖中奖纪录
    public function getAllDrawRecoud($userid,$page,$pagesize){
        $luckyDrawRecordDao = new LuckyDrawRecordDao();
        $record = $luckyDrawRecordDao->alias('r')->join(['tb_lucky_draw'=>'d'],'d.id = r.lucky_draw_id')
            ->where(['d.type'=>['in',[1,3]],'award_id'=>['gt',0],'employee_id'=>$userid])->limit($page*$pagesize,$pagesize)->select();
        array_walk($record,[$this,'addtionAwardName']);
        return $record;

    }



}