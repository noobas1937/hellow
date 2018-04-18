<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/8 0008
 * Time: 下午 4:27
 */

namespace gyo2o\dao;


use think\Model;

class TbpackageDao extends Model
{
    protected $table = 'tb_package';
    protected $append = ['rec_yn_text'];

    private $rec_yn = ['不推荐','推荐'];

    public function getRcyn(){
        return $this->rec_yn;
    }
    public function getRecyntextAttr($value,$data){
        return $this->rec_yn[$data['rec_yn']];
    }

}