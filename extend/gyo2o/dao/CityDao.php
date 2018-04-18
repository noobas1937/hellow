<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7 0007
 * Time: 下午 5:57
 */

namespace gyo2o\dao;


use think\Model;

class CityDao extends Model
{
    // 表名
    protected $name = 'city';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    // 追加属性
    protected $append = [

    ];





}
