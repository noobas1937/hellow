<?php

namespace gyo2o\model;

use gyo2o\BaseModel;
use gyo2o\dao\DataDictionaryDao;
use gyo2o\dao\UserAddressDao;
use gyo2o\dao\UserStatusDao;

class UserAddress extends BaseModel
{
    public function get_by_address($param){
        $address = new UserAddressDao();
        $result = $address->get_by_uid($param['user_id'],$this->page,$this->page_size);
        $data = new DataDictionaryDao();
        foreach($result['data'] as $k => $v){
            $v['province'] = $data->get_by_id($v['province']);
            $v['city'] = $data->get_by_id($v['city']);
            $v['area'] = $data->get_by_id($v['area']);
        }
        return $result;
    }

    public function add_address($post){
        $user_address = new UserAddressDao();
        if ($post['is_default'] == 1) {
            $user_address->set_default($post['user_id']);
        }
        $pay_id = isset($post['pay_id']) ? $post['pay_id'] : null;
        unset($post['pay_id']);
        $add_id = $user_address->add_address($post);
        if ($add_id) {

            $array = array(
                "pay_id" =>$pay_id,
                "add_id" => $add_id,
            );
        } else {
            $array = '新增失败';
        }
        return $array;
    }

    public function del_data($post)
    {
        $user_address = new UserAddressDao();
        $del = $user_address->del_id($post['address_id'],$post['user_id']);
        if ($del) {
            return $del;
        } else {
            return '删除失败';
        }
    }

    public function set_data($post)
    {
        $user_address = new UserAddressDao();
        if ($post['is_default'] == 1) {
            $user_address->set_default($post['user_id']);
        }
        $pay_id = isset($post['pay_id']) ? $post['pay_id'] : null;
        $address_id = isset($post['address_id']) ? $post['address_id'] : null;
        unset($post['pay_id']);
        unset($post['address_id']);
        $edit = $user_address->set_id($address_id,$post['user_id'], $post);
        if ($edit) {
            $array = array(
                "pay_id" =>$pay_id,
                "add_id" => $address_id,
            );
            return $array;
        } else {
            return '新增失败';
        }
    }

    public function get_info($param){
        $user_address = new UserAddressDao();
        return $user_address->get_by_id($param['address_id']);
    }

    public function add_user_status($param){
        $userStatusDao = new UserStatusDao();
        $data = $userStatusDao->get_by_userid($param['user_id']);
        if($data){
            $result = $userStatusDao->set_userid($param['user_id'],$param);
        }else{
            $result = $userStatusDao->insert($param);
        }
        $data = $userStatusDao->get_by_userid($param['user_id']);
        if($result){
            return $data;
        }else{
            return false;
        }
    }

}
