<?php

namespace app\admin\validate;

use gyo2o\model\Package;
use think\Validate;

class PackageDao extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'start_time' =>'packageAddCheck',
        'end_time' =>"packageEditCheck",
    ];
    /**
     * 提示消息
     */
    protected $message = [
        'end_time'=>'起止时间与其它套餐重复',
        'start_time'=>'起止时间与其它套餐重复',
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add' =>['start_time'],
        'edit' => ['end_time'],
    ];


    //编辑时校验
    protected function packageEditCheck($value,$stat,$data){
        $ids = input('ids');
        $package = new Package();
        $result = $package->editCheck($ids,$data['start_time'],$data['end_time']);
        if(is_array($result)){
            $this->message['end_time'] = $result['msg'];
            return false;
        }
        return $result;
    }

    protected function packageAddCheck($value){
        $package = new Package();
        return $package->addCheck($value);
    }
}
