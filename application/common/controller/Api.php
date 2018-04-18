<?php

namespace app\common\controller;

use think\Controller;
use think\controller\Rest;

class Api extends Controller
{

    const RESULT_STATUS_EXCEPTION = 2;
    const RESULT_SUCCESS = 3;
    const RESULT_ERROR = 4;

    public $ajaxMsg = ['code'=>self::RESULT_ERROR, 'msg'=>'数据为空' ,'data'=>null];

//    public function _initialize()
//    {
//        parent::_initialize();
//        header('Access-Control-Allow-Origin:*');
//    }
    /**
     * 返回列表数据
     * @param $result
     * @param string $success_msg
     * @param string $fail_msg
     * @return array
     */
    protected function return_list($result,$success_msg = '查询成功',$fail_msg = '暂无数据'){
        if($result && !is_string($result)){
            $this->ajaxMsg['code'] = self::RESULT_SUCCESS;
            $this->ajaxMsg['data'] = $result['data'];
            $this->ajaxMsg['page'] = ['total'=>$result['total'],'last_page'=>$result['last_page']];
            $this->ajaxMsg['msg'] = $success_msg;
        }else{
            $this->ajaxMsg['code'] = self::RESULT_ERROR;
            $this->ajaxMsg['msg'] = is_string($result) ? $result : $fail_msg;
        }
        return $this->ajaxMsg;
    }

    /**
     * 返回单条数据
     * @param $result
     * @param string $success_msg
     * @param string $fail_msg
     * @return array
     */
    protected function return_dao($result,$success_msg = '成功',$fail_msg = '暂无数据'){
        if($result && !is_string($result)){
            $this->ajaxMsg['code'] = 3;
            $this->ajaxMsg['data'] = $result;
            $this->ajaxMsg['msg'] = $success_msg;
        }else{
            $this->ajaxMsg['code'] = self::RESULT_ERROR;
            $this->ajaxMsg['msg'] = is_string($result) ? $result : $fail_msg;
        }
        return $this->ajaxMsg;
    }

    /**
     * 自动验证处理
     * @param $validate
     * @return array|bool
     */
    protected function return_validate($validate){
        if($validate === true){
            return true;
        }else{
            $this->ajaxMsg['code'] = self::RESULT_STATUS_EXCEPTION;
            $this->ajaxMsg['msg'] = $validate;
        }
        return $this->ajaxMsg;
    }

}
