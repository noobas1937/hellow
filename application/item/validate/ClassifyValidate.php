<?php
/**
 * Created by PhpStorm.
 * User: ggjrw
 * Date: 17-3-15
 * Time: 下午5:13
 */
namespace app\item\validate;

use think\Validate;

class ClassifyValidate extends Validate{
    protected $rule = [
        'item_id' => 'require|number',
        'user_id' => 'require|number',
    ];

    protected $message = [

    ];

    protected $scene = [
        'class_list' => ['page_size'=>'number'],
        'item_list' => ['page_size'=>'number','city_id'=>'number'],
        'item_detail' => ['item_id' => 'require|number'],
        'cart_list' => ['user_id' => 'require|number'],
        'cart' => ['item_id' => 'require|number','user_id' => 'require|number','num'=>'number'],
        'cate_list' => ['cate_id' => 'require|number','user_id' => 'require|number','city_id'=>'number'],
    ];
}

