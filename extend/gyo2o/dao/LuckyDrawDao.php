<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27 0027
 * Time: 上午 11:22
 */

namespace gyo2o\dao;


use think\Model;

class LuckyDrawDao extends Model
{
    protected $table = 'tb_lucky_draw';

    public function getById($luckyDrawId){
        if(empty($luckyDrawId)){
            return false;
        }
        return $this->where('id',$luckyDrawId)->find();
    }

    //积分夺宝活动剩余名额减1
    public function leftNumberMinusOne($drawid,$oldLeft,$number){
        return $this->where(['id'=>$drawid,'left_with_people'=>$oldLeft])->setDec('left_with_people',$number);
    }

    //获取夺宝活动ID集合
    public function getluckyids($page,$pagesize){
        return $this->where(['type'=>2,'status'=>['in',[1,2]]])->limit($page*$pagesize,$pagesize)->order('end_date','desc')->column('id');
    }

    //获取最新两条夺宝活动
    public function getNewstTwo($limit){
        return $this->where(['type'=>2,'end_date'=>['gt',date('Y-m-d H:i:s')]])->order('ishot','desc')->order('start_date')->limit($limit)->select();
    }

    /**
     * 获取已经开始活动记录，最先结束的在前面，同一结束时间的开始时间早的优先
     * @param $type
     * @return array
     */
    public function getActive($type = 1){
        //SELECT * FROM `tb_lucky_draw` WHERE type =1 AND UNIX_TIMESTAMP(`end_date`) >= unix_timestamp(now()) ORDER BY end_date asc,start_date asc
        $where = sprintf('type = %s and UNIX_TIMESTAMP(`start_date`) <= %s and UNIX_TIMESTAMP(`end_date`) >= %s',$type,time(),time());
        $list = $this->where($where)->order('end_date asc,start_date desc')->select();
        if($list) {
            $list = collection($list)->toArray();
        }
        return $list;
    }

    /**
     * 获取等待活动记录，最先开始的在前面，同一开始时间的结束时间早的优先
     * @param $type
     * @return array
     */
    public function getWait($type = 1){
        //$end_time = $end_time?$end_time:time();
        //SELECT * FROM `tb_lucky_draw` WHERE UNIX_TIMESTAMP(`start_date`) >= unix_timestamp(now()) ORDER BY start_date asc,end_date asc
        $where = sprintf('type = %s and UNIX_TIMESTAMP(`start_date`) > %s',$type,time());
        $list = $this->where($where)->order('start_date asc,end_date asc')->select();
        if($list) {
            $list = collection($list)->toArray();
        }
        return $list;
    }

}