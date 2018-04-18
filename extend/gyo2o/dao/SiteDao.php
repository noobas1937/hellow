<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/30 0030
 * Time: 下午 5:04
 */

namespace gyo2o\dao;


use think\Model;

class SiteDao extends Model
{
    // 表名
    protected $name = 'site';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    // 追加属性
    protected $append = [

    ];

    //按条件计算总条数
    public function getTotal($where,$sort,$order){
        return $this->where($where)
            ->order($sort, $order)
            ->count();
    }

    //按条件获取站点列表
    public function getSites($where,$sort,$order,$offset,$limit){
        return $this->where($where)
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->select();
    }

}