<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/12 0012
 * Time: 上午 9:40
 */

namespace gyo2o\dao;


use think\Model;

class AreaDao extends Model
{
    protected $table = 'tb_area';


    public function get_list($map = []){
        $result = $this->where($map)->select();

        return $result;
    }
}