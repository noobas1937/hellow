<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/19 0019
 * Time: 下午 4:03
 */

namespace gyo2o\model;


use gyo2o\BaseModel;
use gyo2o\dao\UserStatusDao;

class UserStatus extends BaseModel
{

    public function get_by_userid($userid)
    {
        $userStatusDao = new UserStatusDao();
        return $userStatusDao->get_by_userid($userid);
    }

    public function remove_health_by_userid($userid)
    {
        $userStatusDao = new UserStatusDao();
        if (false == $userStatusDao->get_by_userid($userid)) {
            $data['user_id'] = $userid;
            $data['home_health'] = 1;
            return $userStatusDao->save($data);
        } else {
            return $userStatusDao->set_home_health(1, $userid);
        }

    }

    public function add_city($city_id,$province_id){
        if($city_id == true && $province_id == true){
            $arr = array(
                'user_id' => session('userid'),
                'city_id' => $city_id,
                'province_id' => $province_id
            );
            $data = $this->get_by_userid(session('userid'));
            $userStatusDao = new UserStatusDao();
            if($data == true){
                $result = $userStatusDao->set_userid(session('userid'),$arr);
            }else{
                $result = $userStatusDao->save($arr);
            }
            if($result){
                $array = array("status" => "success", "code" => 100000);
                return $array;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}