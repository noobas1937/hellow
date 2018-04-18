<?php
/**
 * Created by PhpStorm.
 * User: liuwen
 * Date: 16/6/3
 * Time: 下午5:46
 */

namespace telephone;

class Voice
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
        $this->data['smsg'] = $msg;

        $url = $this->serviceUrl . '/g_Submit';

        $request = new Request();
        $response = $request->fsockopen($this->data, $url);

        if ($response) {
            return true;
        }else{
            return false;
        }
    }
}