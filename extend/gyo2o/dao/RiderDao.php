<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/28 0028
 * Time: 上午 11:18
 */

namespace gyo2o\dao;


use gyo2o\model\Common;
use think\Db;
use think\Model;

class RiderDao extends Model
{
    // 表名
    protected $name = 'rider';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;

    // 追加属性
    protected $append = [
        'sex_text',
        'type_text',
        'jointime_text',
        'leavetime_text',
        'stationmaster_text',
        'workstatus_text',

    ];

    protected $readonly = ['starttime','endtime'];
    
    public function getSexlist()
    {
        return ['男' => __('男'),'女' => __('女')];
    }

    public function getTypelist()
    {
        return ['试用期员工' => __('试用期员工'),'正式员工' => __('正式员工'),'已离职' => __('已离职')];
    }

    public function getStationmasterlist()
    {
        return ['否' => __('否'),'是' => __('是')];
    }

    public function getWorkstatuslist()
    {
        return ['工作' => __('工作'),'休假' => __('休假')];
    }


    public function getSexTextAttr($value, $data)
    {
        $value = $value ? $value : $data['sex'];
        $list = $this->getSexList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['type'];
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getJointimeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['jointime'];
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getStationmasterTextAttr($value, $data)
    {
        $value = $value ? $value : $data['stationmaster'];
        $list = $this->getStationmasterList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getLeavetimeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['leavetime'];
        if(empty($value)){
            return '-';
        }
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    public function getWorkstatusTextAttr($value, $data)
    {
        $value = $value ? $value : $data['workstatus'];
        $list = $this->getWorkstatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    protected function setJointimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    protected function setLeavetimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    //获取（全部/站点）骑手列表
    public function getRiders($where,$sort,$order,$offset,$limit,$site_id){

        if(empty($site_id)){
            $list = $this
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
        }else{
            $list = $this
                ->where($where)
                ->where("site_id=$site_id")
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
        }

        return $list;

    }

    //获取符合条件的总记录数
    public function getTotal($where,$sort,$order,$site_id){
        if(empty($site_id))
            return $this->where($where)->order($sort, $order)->count();
        return $this->where($where)->where("site_id=$site_id")->order($order)->count();
    }

    //按ID获取骑手信息
    public function getRider($riderid){
        return $this->find($riderid);
    }

    //按条件获取站点骑手统计信息
    public function statisticBySite($where){
        return $this->field('site_id,count(id) pnu')->where($where)->group('site_id')->select();
    }

    //按站点获取站点在职骑手
    public function getRiderBySite($siteid,$time){
        return Db::name('rider')->field('id,name')->where("site_id = $siteid and jointime<=$time and (leavetime = 0 or leavetime>$time)")->select();
    }

    //根据身份证获取骑手信息
    public function getByIdcard($idcard){
        return $this->where("idcard='$idcard'")->find();
    }

    //根据openID获取骑手信息
    public function getRiderByOpenid($openid){
        return $this->where("open_id='$openid'")->find();
    }

    //根据userid获取骑手信息
    public function getRiderByUserid($userid){
        return $this->where("user_id=$userid")->find();
    }
}