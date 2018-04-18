<?php
/**
 * Created by PhpStorm.
 * User: ggjrw
 * Date: 17-3-15
 * Time: 下午5:13
 */
namespace app\ad\validate;

use think\Validate;

class AdValidate extends Validate{
    protected $rule = [
        'city_id' => 'number',
        'type'=>'require|number'
    ];

    protected $message = [
        'city_id.number '=> '城市id只能为数字',
        'type.require' => '类型不能为空',
        'type.number' => '类型只能为数字',
    ];

    protected $scene = [
        'ad_list' => ['type'=>'require|number','city_id'=>'number'],
        'news_list' => ['city_id'=>'number']
    ];
}

