<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/8 0008
 * Time: 下午 5:14
 */

namespace gyo2o\dao;


use think\Model;

class PackageItemDao extends Model
{
    protected $table = 'tb_package_item';


    /*
  * 获取商品属于那个package
  * @Author: liuwen
  * @Date:2016/09/01
  * */
    public function get_by_item($item)
    {
        $map = array(
            'item_id' => $item,
        );
        $result = $this->where($map)->find();

        return $result;
    }


    public function get_by_package($package)
    {
        $map = array(
            'package_id' => $package,
        );
        $result = $this->where($map)->select();

        return $result;
    }

}