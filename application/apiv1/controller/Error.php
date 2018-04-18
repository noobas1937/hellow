<?php
/**
 * Created by PhpStorm.
 * User: liuwen
 * Date: 17/7/19
 * Time: 上午9:28
 */

namespace app\apiv1\controller;

use gyo2o\model\ApiAuthList;
use gyo2o\model\ApiList;
use think\Controller;
use think\Exception;
use think\exception\ClassNotFoundException;
use think\response\Json;

class Error extends Controller
{

    public function _empty()
    {
        header('Access-Control-Allow-Origin:*');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        $this->request->filter('trim');
//            if($_REQUEST){
//
//            }

        if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
            return 1;
        }
        try{
            $result = ['code' => 1, 'msg' => 'api access failer'];
            $api_name = input('param.action');
            if($api_name){
                $api_list = new ApiList();
                $api_auth = new ApiAuthList();
                $header = $this->request->header('X-Requested-With');
                if(in_array($api_name,$api_auth->authList) && $header =='gzh'){
                    $param = $this->request->param();
                    if(empty($param['user_id'])||$param['user_id']<0){
                        return json(['code'=>1122,'status'=>'failer','msg'=>'需要激活']);
                    }
                }
                $action = $api_list->api[$api_name];
                $result = action($action);
                if ($result instanceof Json) {
                    return $result;
                } else {
                    $result['msg'] = 'response data is error';
                }
            }
        }catch (\ReflectionException $e) {
            $result['msg'] = 'api not found';
        } catch (ClassNotFoundException $e) {
            $result['msg'] = 'api run exception';
        }
//        catch (Exception $e){
//            error_log($e."\n\r",3,'fail.txt');
//        }
        error_log(json_encode($_POST)."\n\r",3,'json.txt');
        return Json::create($result)->code(200);
    }
}
