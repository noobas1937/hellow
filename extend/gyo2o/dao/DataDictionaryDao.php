<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1 0001
 * Time: 下午 5:26
 */

namespace gyo2o\dao;


use think\Model;

class DataDictionaryDao extends Model
{
    protected $table = 'tb_data_dictionary';

    /**
     * 根据key获取数据
     */
    public function get_by_res_key($res_key)
    {
        $where = array(
            'ishide' => 0,
            'resKey' => $res_key
        );
        return $this->where($where)->select();
    }

    /**
     * 根据pid获取数据
     */
    public function get_by_pid($pid)
    {
        $where = array(
            'ishide' => 0,
            'parentId' => $pid
        );
        return $this->where($where)->select();
    }

    public function get_by_id($id)
    {
        $where = array(
            'ishide' => 0,
            'id' => $id
        );
        return $this->where($where)->find();
    }

    public function getName($id){
        return $this->where('id',$id)->value('name');
    }
}