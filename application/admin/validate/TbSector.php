<?php

namespace app\admin\validate;

use think\Validate;

class TbSector extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'name' => 'require',
        'mobile' => 'require',
        'enterprise_id' => 'require',
    ];
    /**
     * 提示消息
     */
    protected $message = [
        'name.require' => '部门名称不能为空',
        'mobile.require' => '联系电话不能为空',
        'enterprise_id.require' => '所属企业不能为空'
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => [
            'name' => 'require',
            'mobile' => 'require',
            'enterprise_id' => 'require',
        ],
        'edit' => [
            'name' => 'require',
            'mobile' => 'require',
        ],
    ];
}
