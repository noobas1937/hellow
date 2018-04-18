<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/28 0028
 * Time: 下午 3:09
 */

namespace gyo2o\dao;


use think\Model;

class AccountDao extends Model
{
    protected $table = 'tb_user_account';
//    protected $visible = ['id','mobile','reg_from','status',];
    protected $append = ['reg_text','status_text'];

    private $reg_from = ['1'=>'微信','2'=>'后台','3'=>'app'];
    private $status = ['0'=>'正常','1'=>'禁用'];
    public function getRegtextAttr($value,$data){
        return $this->reg_from[$data['reg_from']];
    }

    public function getStatustextAttr($value,$data){
        return $this->status[$data['status']];
    }

    public function getStatusList(){
        return $this->status;
    }

    public function getRegList(){
        return $this->reg_from;
    }

    //根据条件获取前台用户列表
    public function getAcounts($where, $sort, $order, $offset, $limit){
        return $this->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
    }

    //根据条件统计记录数目
    public function getAcountNumber($where){
        return $this->where($where)->count();
    }


    public function getAccountName($userid)
    {
        return $this->where('id', $userid)->value('account_name');
    }

    public function get_by_id($userId)
    {
            $where['id'] = $userId;
        return $this->where($where)->find();
    }
}