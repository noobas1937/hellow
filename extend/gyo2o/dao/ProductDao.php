<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6 0006
 * Time: 下午 5:57
 */

namespace gyo2o\dao;


use think\Model;

class ProductDao extends Model
{
    protected $table = 'tb_item_product';


    //符合条件的记录数
    public function getTotal($where)
    {
        return $this->where($where)->count();
    }

    //符合条件的记录页
    public function getProducts($where,$sort,$order,$offset,$limit)
    {
        return $this->where($where)->order($sort,$order)->limit($offset,$limit)->select();
    }

    public function getById($id)
    {
        return $this->where('id',$id)->find();
    }
}