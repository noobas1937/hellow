<?php

namespace app\admin\validate;

use think\Validate;

class TbEnterprise extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'name' => 'require'
    ];
    /**
     * 提示消息
     */
    protected $message = [
        'name.require' => '企业名称不能为空'
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => ['name' => 'require'],
        'edit' => ['name' => 'require'],
    ];
    
}
