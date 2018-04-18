<?php
/**
 * Created by PhpStorm.
 * User: ggjrw
 * Date: 18/2/12
 * Time: 下午8:33
 */

namespace app\party\controller;


use think\Controller;

class Year extends Controller
{
    public function index(){
        return $this->view->fetch();
    }

    public function gift(){
        header('Access-Control-Allow-Origin:*');
        $response['code'] = 1;
        $response['msg'] = 'success';

        $data['type'] = rand(1,2);
        $data['count'] = 20;
        if(1 == $data['type']){
            $data['expire']['hour'] = 0;
            $data['expire']['min'] = 0;
            $data['expire']['sec'] = 8;
        }else{
            $data['count'] = [1,3,5,10][rand(0,3)];
        }

        for($i=0; $i<$data['count']; $i++){
            $data['list'][] = ["name_$i","tel_$i","gift_$i"];
        }
        $response['data'] = $data;
        return json($response);
    }

}