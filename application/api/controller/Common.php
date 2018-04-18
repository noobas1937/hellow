<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27 0027
 * Time: 上午 11:20
 */

namespace app\api\controller;


use think\Controller;

class Common extends Controller
{
    protected $errstr = '';
    protected $code = 100000;

    public function _initialize()
    {
        parent::_initialize();

        //设置过滤方法
        $this->request->filter(['strip_tags', 'htmlspecialchars']);
        header('Access-Control-Allow-Origin:*');
    }

    public function jsonsuccess($data){
        return json(['code'=>$this->code,'mesg'=>$this->errstr,'status'=>'success','data'=>$data]);
    }

    public function jsonfailer($data){
        return json(['code'=>$this->code,'mesg'=>$this->errstr,'status'=>'failer','data'=>$data]);
    }

    public function apireturn($data){
        if(empty($this->errstr)){
            return $this->jsonsuccess($data);
        }else{
            return $this->jsonfailer($data);
        }
    }
}