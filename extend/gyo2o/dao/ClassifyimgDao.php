<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/30 0030
 * Time: 下午 3:10
 */

namespace gyo2o\dao;


use think\Model;

class ClassifyimgDao extends Model
{
    protected $table = 'tb_classify_img';

    //根据classify id获取图片
    public function getClassifyImg($classifyId)
    {

    }

    /**
     * 查询图片
     * @param $class_id
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function get_by_class_id($class_id)
    {
        return $this->where('type = 2 and classify_id = ' . $class_id)->order('seq desc')->select();
    }

    /**
     * 查询分类图片
     * @param $class_id
     * @return array|false|\PDOStatement|string|Model
     */
    public function get_logo_by_class_id($class_id)
    {
        return $this->where('type = 1 and classify_id = ' . $class_id)->order('seq desc')->find();
    }

}