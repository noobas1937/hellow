<?php
/**
 * Created by PhpStorm.
 * User: ggjrw
 * Date: 17/11/8
 * Time: 下午2:29
 */

// +----------------------------------------------------------------------
// | 缓存设置
// +----------------------------------------------------------------------
return [
    // 驱动方式
    'type'   => 'File',
    // 缓存保存目录
    'path'   => CACHE_PATH,
    // 缓存前缀
    'prefix' => '',
    // 缓存有效期 0表示永久缓存
    'expire' => 3600,
];