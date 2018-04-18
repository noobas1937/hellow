<?php
namespace app\order\validate;
use think\Validate;

class OrderValidate extends Validate{
    protected $rule = [
        'user_id' => 'require|number',
        'pay_id' => 'require|number',
    ];

    protected $message = [
    ];

    protected $scene = [
        'order_confirm' => ['user_id' => 'require|number'],
        'add_order' => ['user_id' => 'require|number','pay_id' => 'require|number'],
        'confirm_data' => ['pay_id' => 'require|number'],
        'get_status_list' => ['user_id' => 'require|number','status' => 'require|number'],
        'get_detail' => ['user_id' => 'require|number','sn2' => 'require|number'],
    ];
}

