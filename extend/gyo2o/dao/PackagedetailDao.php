<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/28 0028
 * Time: 下午 2:29
 */

namespace gyo2o\dao;


use think\Model;

class PackagedetailDao extends Model
{
    // 表名
    protected $name = 'packagedetail';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    // 追加属性
    protected $append = [

    ];

    //按条件获取套餐菜品
    public function getPackagedetail($id){
        return $this
            ->where('package_id' , '=', $id)
            ->select();
    }

    //统计套餐下餐品
    public function getCountByPackageId($id){
        return $this
            ->where('package_id' , '=', $id)
            ->count();
    }

    //按条件获取当月菜品
    public function getDisheBymonty($where)
    {
        return $this->where($where)->order('date desc')->select();
    }

    public function getDishesByDate($date){
        return $this->where("date = '$date'")->select();
    }

}