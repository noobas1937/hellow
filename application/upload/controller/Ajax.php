<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/18 0018
 * Time: 下午 4:53
 */

namespace app\upload\controller;

use app\common\controller\Api;
use gyo2o\model\Attachment;
use think\Config;


class Ajax extends Api
{
    public function upload(){
        header('Access-Control-Allow-Origin:*');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        Config::set('default_return_type', 'json');
        $file = $this->request->file('file');
        if (empty($file))
        {
            $this->error("未上传文件或超出服务器上传限制");
        }

        $attachment = new Attachment();
        $result =  $attachment->uploadImg($file);
        if($result){
            return json(['status'=>'success','msg'=>'上传成功','code'=>3,'data'=>$result]);
        }

        return json(['status'=>'failer','msg'=>'上传失败','code'=>4,'data'=>[]]);

    }



}