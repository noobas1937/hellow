<?php
namespace app\api\validate;
use think\Validate;

class AjaxValidate extends Validate{
    protected $rule = [

    ];

    protected $message = [

    ];

    protected $scene = [
        'ajax_area' => ['pid' => 'require|number'],
        'apply_list' => ['employee_id' => 'require|number']
    ];
}

