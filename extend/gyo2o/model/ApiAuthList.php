<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/21 0021
 * Time: 下午 4:19
 */

namespace gyo2o\model;


use gyo2o\BaseModel;

class ApiAuthList extends BaseModel
{
    //需要认证后才能访问的接口
    public $authList = [
        'lucky.get.fdjcount',//获取奋斗金总数
        'user.post.singlsalarydetail',//单条工资明细
        'user.post.userpointconfirm',//工资员工确认
        'user.get.balancedetail',//现金明细
        'user.get.pointdetail',//奋斗金明细
        'user.get.salarydetail',//工资明细
        'user.post.withdraw',//提现申请
        'user.post.conversion' ,//奋斗金兑换现金
        'user.get.alldrawrecord' ,//用户所有抽奖活动中奖纪录
        'lucky.get.luckyticket',//获取用户抽奖券
        'lucky.get.luckynumber',//获取用户夺宝幸运码
        'lucky.get.luckyapplyrecord',//用户参与的夺宝活动
        'lucky.get.luckyapply',//积分夺宝报名
        'user.get.luckdrawrecord',//获取单个活动用户中奖纪录
        'user.get.myinfo',//个人信息
        'lucky.get.newluckyapply',//使用夺宝券的积分夺宝报名
        'lucky.get.luckyparam',//夺宝参数计算
        'lucky.get.luckynumber',//获取用户夺宝幸运码
    ];

    public $diff = [
        'user.get.usercenter'=>'user/ajax/usercenter',//个人中心数据
        'lucky.get.luckyapplyinfo' => 'lucky/ajax/luckyapplyinfo',//积分夺宝活动信息
    ];

}