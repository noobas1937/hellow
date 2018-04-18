<?php

namespace app\admin\validate;

use think\Validate;

class TbEmployee extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'sector_id' => 'require|number',
        'contact_moblie' => 'require|number',
        'identity_id' => 'require|number',
        'credits' => 'require|number|min:1',
        'enterprise_id' => 'require|number',
    ];
    /**
     * 提示消息
     */
    protected $message = [
        'sector_id.require' => '所属部门不能为空',
        'contact_moblie.require' => '联系电话不能为空',
        'identity_id.require' => '身份不能为空'
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => [
            'enterprise_id' => 'require|number',
            'sector_id' => 'require|number',
            'contact_moblie' => 'require|number',
            'identity_id' => 'require|number',
        ],
        'edit' => [
            'sector_id' => 'require|number',
            'contact_moblie' => 'require|number',
            'identity_id' => 'require|number',
        ],
        'add_record'  => [
            'credits' => 'require|number|min:1'
        ],
    ];
    
}
