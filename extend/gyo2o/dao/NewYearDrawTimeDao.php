<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/12 0012
 * Time: 上午 11:15
 */

namespace gyo2o\dao;


use think\Model;

class NewYearDrawTimeDao extends Model
{
    protected $table = 'tb_newyear_drawtime';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    // 追加属性
    protected $append = [
        'starttime_text',
        'end_time_text'
    ];






    public function getStarttimeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['starttime'];
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getEndtimeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['end_time'];
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setStarttimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    protected function setEndtimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

}