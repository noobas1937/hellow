<?php
/**
 * Created by PhpStorm.
 * User: liuwen
 * Date: 16/6/6
 * Time: 下午2:17
 */

namespace telephone;
abstract class Telephone
{
    public $serviceUrl = '';
    public $accessKey = '';
    public $sercetKey = '';
    public $signName = '';

    public function __construct()
    {   $sms =  \think\Config::get('sms');
        $this->serviceUrl = $sms['SMS_SERVICE_URL'];
        $this->accessKey = $sms['SMS_ACCESS_KEY'];
        $this->sercetKey = $sms['SMS_SECRET_KEY'];
        $this->signName = $sms['SMS_SIGN_NAME'];

    }

}