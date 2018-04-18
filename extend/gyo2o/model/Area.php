<?php

namespace gyo2o\model;

use gyo2o\BaseModel;
use gyo2o\dao\AreaDao;
use think\exception\PDOException;

class Area extends BaseModel
{
    public function get_list($param){
        $area = new AreaDao();
        try {
            $param['pid'] = isset($param['pid']) ? $param['pid'] : 1;
            $map = [];
            if($param['pid'] == 0){
                $param['pid'] = 1;
            }
            $map['pid'] = $param['pid'];
            if($param['pid'] == 1){
                $api_list = new ApiList();
                $describe = $api_list->describe;
                $ids = '';
                foreach ($describe as $key=>$value) {
                    if($value['id'] == $param['area_id']){
                        if(!is_array($value['area_id'])){
                            if($value['area_id'] > 0){
                                $ids = $value['area_id'];
                            }
                        }else{
                            $ids = join(',',$value['area_id']);
                        }
                    }
                }
                $map['id'] = ['in',$ids];
            }
            $result = collection($area->get_list($map))->toArray();
        }catch(PDOException $e){
            return $e->getMessage();
        }
//        $result = $this->city_tree($result);
//        print_r($result);
        return $result;
    }


//    protected function findChild($arr,$children = '',$pid){
//        static $tree = array();
//        if($pid !== 0){
//            $children = '|--&nbsp;'.$children;
//        }
//
//        foreach ($arr as $key=>$val){
//            $result[$key]['child'] = $children;
//            $result[$key]['name'] = $val['name'];
//            $result[$key]['id'] = $val['id'];
//            $tree[] = $result[$key];
//            if ($val['children']){
//                $this -> findChild($val['children'],$children,1);
//            }
//        }
//
//        return $tree;
//    }


    public function city_tree($data){
        $city = new AreaDao();
        if(!empty($data)){
            foreach ($data as $key => $val) {
                $data[$key]['children'] = collection($city->where(['pid' => $val['id']])->select())->toArray();
                if($data[$key]['children']){
                    $data[$key]['children'] = $this->city_tree($data[$key]['children']);
                }else{
                    unset($data[$key]['children']);
                }
            }

            return $data;
        }
    }
}
