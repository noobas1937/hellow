<?php

namespace app\admin\validate;

use think\Validate;

class PackagedetailDao extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'date'=>'checkPackage:add',
        'package_id'=>'checkPackage:edit'

    ];
    /**
     * 提示消息
     */
    protected $message = [

    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => ['date'],
        'edit' => ['package_id'],
    ];

    protected function checkPackage($value,$method,$data){
        //套餐内日期必须限制在套餐期限内，且不能重复
        $packagedetail = new \gyo2o\model\Packagedetail();
        $result =  $packagedetail->checkDate($data['date'],$data['package_id'],$method);
        if($result && is_bool($result)){
            return true;
        }

        if(is_array($result)){
            if($method == 'add'){
                $this->message['date'] = $result['msg'];
            }else{
                $this->message['package_id'] = $result['msg'];
            }

            return false;
        }

    }
}
