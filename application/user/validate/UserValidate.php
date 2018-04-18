<?php
namespace app\user\validate;
use think\Validate;

class UserValidate extends Validate{
    protected $rule = [
        'user_id' => 'require|number',
        'employee_id' => 'require|number',
        'bank_name' => 'require',
        'bank_card' => 'require|number',
        'branch_name' => 'require',
        'username' => 'require'
    ];

    protected $message = [
    ];

    protected $scene = [
        'user_id' => ['user_id' => 'require|number'],
        'get_openid' => ['openid' => 'require','client' => 'require'],
        'add_address' => [
            'user_id' => 'require|number',
            'name' => 'require',
            'mobile' => 'require|number',
            'address' =>'require',
            'is_default' =>'number'
        ],
        'remove_address' => [
            'user_id' => 'require|number',
            'address_id' => 'require|number',
        ],
        'city_list' => [
            'user_id' => 'require|number',
            'pid' => 'require|number'
        ],
        'add_user_status' => [
            'user_id' => 'require|number',
            'city_id' => 'require|number',
            'province_id' => 'require|number',
        ],
        'employee_apply' => [
            'tb_user_id' => 'require|number',
            'contact_moblie' => 'require',
            'name' => 'require',
            'idcard' => 'require',
            'id_card_positive_imgid' => 'require',
            'id_card_opposite_imgid' => 'require',
            'id_card_hold_imgid' => 'require',
        ],
        'employee_bank' => [
            'employee_id' => 'require|number',
            'bank_name' => 'require',
            'bank_card' => 'require|number',
            'branch_name' => 'require',
            'username' => 'require'
        ]
    ];
}

