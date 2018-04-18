<?php
/**
 * Created by PhpStorm.
 * User: ggjrw
 * Date: 17/11/8
 * Time: 下午2:49
 */

use think\Config;

return [
    'deny_module_list' => ['common', 'extra' , 'test'],

    'app_trace' => true,

    'api_debug_switch' => true,

    'app_debug' => true,

    'controller_auto_search' => true,
];