<?php
/**
 * Created by PhpStorm.
 * User: ggjrw
 * Date: 17/11/8
 * Time: 下午2:29
 */

// +----------------------------------------------------------------------
// | 会话设置
// +----------------------------------------------------------------------
return [
    'id'             => '',
    // SESSION_ID的提交变量,解决flash上传跨域
    'var_session_id' => '',
    // SESSION 前缀
    'prefix'         => 'tt_qq',
    // 驱动方式 支持redis memcache memcached
    'type'           => '',
    // 是否自动开启 SESSION
    'auto_start'     => true,
];