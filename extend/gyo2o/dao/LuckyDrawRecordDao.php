<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/28 0028
 * Time: 上午 9:19
 */

namespace gyo2o\dao;


use think\Model;

class LuckyDrawRecordDao extends Model
{
    protected $table = 'tb_lucky_draw_record';



    public function getByAwardId($where, $sort, $order, $offset, $limit,$awardid){
        return $this->where($where)->where('award_id',$awardid)->order($sort,$order)->limit($offset,$limit)->select();
    }

    public function getByDrawidBackend($where, $sort, $order, $offset, $limit,$drawid){
        return $this->where($where)->where('lucky_draw_id',$drawid)->order('award_id','desc')->order($sort,$order)->limit($offset,$limit)->select();
    }

    public function get_list($where,$sort,$order,$offset,$limit,$ids = null){
        $data = $this->where($where);
        if($ids && $ids > 0){
            $data->where(['employee_id'=>$ids,'award_id' =>['gt',0]]);
        }
        $data->order($sort, $order)
            ->limit($offset, $limit);
        $result = $data->select();

        return $result;
    }

    public function get_count($where){
        return $this->where($where)->count();
    }

    public function edit($id,$data){
        $map = ['id' => $id];
        return $this->save($data,$map);
    }

    public function getByEmployId($employeeid){
        return $this->where('employee_id',$employeeid)->select();
    }

    public function getByEmployIdAndDrawId($employeeid,$drawid,$award = true){
        $this->where('lucky_draw_id',$drawid)->where('employee_id',$employeeid);
        if (false == $award) $this->where('award_id > 0');
        return $this->order('create_date','desc')->select();
    }


    public function getByRecordId($recordid)
    {
        return $this->where('id', $recordid)->find();

    }

    public function get_id($id){
        $map = ['id' => $id];
        return $this->where($map)->find();

    }

    public function getByDrawId($drawid){
        if(empty($drawid)){
            return false;
        }

        return $this->where('lucky_draw_id',$drawid)->select();
    }

    public function getByUseridAndDrawid($userid,$drawid){

        return $this->where(['employee_id'=>$userid,'lucky_draw_id'=>$drawid])->order('award_id','desc')->select();
    }

    //获取活动最新中奖纪录
    public function getPrizeRecord($drawid){
        return $this->where(['lucky_draw_id'=>$drawid,'award_id'=>array('gt',0)])->order('create_date','desc')->limit(1)->select();
    }

    //根据活动id集合获取中奖纪录
    public function getByDrawids($ids){
        return $this->where(['lucky_draw_id'=>array('in',$ids),'award_id'=>array('gt',0)])->order('lucky_draw_id','desc')->select();
    }

    public function getByDrawidCondition($drawid,$page,$pagesize,$isrecord = 0){
        if(empty($isrecord)){
            return $this->where(['award_id'=>['in',[1,2,3]]])->limit($page*$pagesize,$pagesize)->order('create_date','desc')->select();
        }else{
            return $this->where(['award_id'=>['in',[1,2,3]]])->limit($page*$pagesize,$pagesize)->order('create_date','desc')->select();
        }

    }

    public function getLuckyNumber($drawid,$userid){
        return $this->where(['lucky_draw_id'=>$drawid,'employee_id'=>$userid])->select();
    }

    public function getByDayHour(){
        return $this->where(["DATE_FORMAT(create_date,'%Y-%m-%d %H')"=>date('Y-m-d H')])->find();
    }

    public function groupByEmployeeid($drawid){
        return $this->field('employee_id,count(id) as total')->where(['lucky_draw_id'=>$drawid,'create_date'=>['>','2018-02-13 13:13:00'],'useticket'=>0])->group('employee_id')->select();
    }

    public function countTicke($userid,$drawid){
        return $this->where(['employee_id'=>$userid,'lucky_draw_id'=>$drawid,'useticket'=>1])->count();
    }

}