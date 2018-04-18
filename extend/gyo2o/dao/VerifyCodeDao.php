<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/18 0018
 * Time: 上午 11:23
 */

namespace gyo2o\dao;


use think\Model;

class VerifyCodeDao extends Model
{

    protected $table = "tb_verifycode";

    public function getByAccount($account){
        return $this->where('account',$account)->order('create_date','desc')->find();
    }

    public function get_code($code){
        return $this->where('code',$code)->find();
    }

    public function set_status($id,$status){
        return $this->where('id',$id)->setField('is_status',$status);
    }

    public function getByAdminAccount($account){
        return $this->where(['account'=>$account,'type'=>2])->order('create_date','desc')->find();
    }

}