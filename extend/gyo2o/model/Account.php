<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/28 0028
 * Time: 下午 3:07
 */

namespace gyo2o\model;


use function fast\e;
use gyo2o\BaseModel;
use gyo2o\dao\AccountDao;
use gyo2o\dao\UserInfoDao;

class Account extends BaseModel
{
    //根据条件获取前台用户列表
    public function getAccounts($where,$sort,$order,$offset,$limit){
        $accountDao = new AccountDao();
        $total = $accountDao->getAcountNumber($where);
        if($total){
            $rows = $accountDao->getAcounts($where,$sort,$order,$offset,$limit);
            $userInfoDao = new UserInfoDao();
            foreach ($rows as &$row){
                $userinfo = $userInfoDao->where('account_id',$row['id'])->find();
                $row['nickname'] = $userinfo['nickname'];
                $row['birth'] = $userinfo['birth'];
            }

            unset($row);
        }else{
            return ['total'=>0,'rows'=>[]];
        }

        return ['total'=>$total,'rows'=>$rows];
    }

    //根据account_id获取用户详细信息
    public function getUserInfo($ids){
        $userInfoDao = new UserInfoDao();
        $row =  $userInfoDao->where('account_id',$ids)->find();
        if($row){
            return ['total'=>1,'rows'=>[$row]];
        }else{
            return ['total'=>0,'rows'=>[]];
        }
    }

    public function get_by_user_id($user_id)
    {
        $userInfoDao = new UserInfoDao();
        $userInfo = $userInfoDao->get_by_user_id($user_id);

        return $userInfo;
    }


}