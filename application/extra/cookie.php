<?php
/**
 * Created by PhpStorm.
 * User: ggjrw
 * Date: 17/11/8
 * Time: 下午2:27
 */


// +----------------------------------------------------------------------
// | Cookie设置
// +----------------------------------------------------------------------
return [
    // cookie 名称前缀
    'prefix'    => 'tt_qq_',
    // cookie 保存时间
    'expire'    => 60 * 60 * 24 * 7,
    // cookie 保存路径
    'path'      => '/',
    // cookie 有效域名
    'domain'    => '',
    //  cookie 启用安全传输
    'secure'    => false,
    // httponly设置
    'httponly'  => '',
    // 是否使用 setcookie
    'setcookie' => true,
];