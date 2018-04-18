<?php
/**
 * Created by PhpStorm.
 * User: liuwen
 * Date: 16/6/3
 * Time: 下午5:46
 */

namespace telephone;

class SMS extends Telephone
{
    public $sprdid = '';
    public $scorpid = '';
    public $data = array();

    public function __construct()
    {
        parent::__construct();
        $this->data['sname'] = $this->accessKey;
        $this->data['spwd'] = $this->sercetKey;

    }

    public function send($msg, $phone, array $key = array())
    {
        $this->data['scorpid'] = $this->scorpid;
        $this->data['sprdid'] = $this->sprdid;
        $this->data['sdst'] = $phone;
        $this->data['smsg'] = $msg . $this->signName;
        if (0 < sizeof($key)) $this->data['key'] = $key;

        $url = $this->serviceUrl . '/g_Submit';

        $request = new Request();
        $response = $request->fsockopen($this->data, $url);
        if (false == stristr($response, '<State>0</State>')) {
            return false;
        } else {
            return true;
        }
    }
}