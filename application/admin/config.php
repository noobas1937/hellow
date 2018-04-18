<?php

//配置文件
return [
    'url_common_param'       => true,
    'url_html_suffix'        => '',
    'controller_auto_search' => true,

    //分页配置
    'paginate'               => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],
    //验证码配置
    'captcha'                => [
        // 验证码字符集合
        'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
        // 验证码字体大小(px)
        'fontSize' => 16,
        // 是否画混淆曲线
        'useCurve' => false,
        //使用中文验证码
        'useZh'    => false,
        // 验证码图片高度
        'imageH'   => 30,
        // 验证码图片宽度
        'imageW'   => 100,
        // 验证码位数
        'length'   => 4,
        // 验证成功后是否重置
        'reset'    => true
    ],
    //FastAdmin配置
    'fastadmin'              => [
        'version' => '1.0.0.20171026_beta',
        'api_url' => 'http://api.fastadmin.net',
    ],
];
