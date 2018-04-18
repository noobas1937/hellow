<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/26 0026
 * Time: 下午 3:35
 */

namespace app\api\controller;
use app\common\controller\Api;
use gyo2o\model\ApiList;
use gyo2o\model\Area;
use gyo2o\model\EmployeeApply;

class Ajax extends Api
{
    public function ajax_area(){
        $param = input('param.');
        $validate = $this->validate($param,'api/AjaxValidate.ajax_area');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new Area();
            $result = $class->get_list($param);
            $result = $this->return_dao($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function apply_list(){
        $param = input('post.');
        $validate = $this->validate($param,'api/AjaxValidate.apply_list');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new EmployeeApply();
            $result = '';
            $result = $this->return_dao($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function describe_list(){
        $param = input('post.');
        $validate = $this->validate($param,'api/AjaxValidate.describe_list');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new ApiList();
            $result = $this->return_dao($class->describe);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    public function bank_list(){
        $param = input('post.');
        $validate = $this->validate($param,'api/AjaxValidate.bank_list');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new ApiList();
            $result = $this->return_dao($class->bank_list);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

}