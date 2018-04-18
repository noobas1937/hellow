<?php
/**
 * Created by PhpStorm.
 * User: liuwen
 * Date: 17/6/19
 * Time: 下午2:03
 */

namespace gyo2o;


class BaseModel
{
    const FREIGHT = 10;
    const FREEFREIGHT = 100;

    protected $city_id = false;
    protected $page = 1;
    protected $page_size = 5;
    protected $error;
    public function __construct()
    {

        $this->city_id = input('param.city_id') ? input('param.city_id') : 2;
        $this->page = input('param.page') ? input('param.page') : 1;
        $this->page_size = input('param.page_size') ? input('param.page_size') : 5;

    }

    public function get_base_list($data,$model,$action,$ids = null){
        list($where, $sort, $order, $offset, $limit) = $data;
        if($ids && $ids > 0){
            $result = $model->$action($where,$sort,$order,$offset,$limit,$ids);
        }else{
            $result = $model->$action($where,$sort,$order,$offset,$limit);
        }
        return $result;
    }

    public function get_base_count($data,$model,$action){
        list($where) = $data;
        $result = $model->$action($where);
        return $result;
    }

    public function base_add($data,$model,$action){
        $result = $model->$action($data);
        return $result;
    }

    public function base_edit($id,$data,$model,$action){
        $result = $model->$action($id,$data);
        return $result;
    }

    public function base_del($id,$model,$action){
        if(strpos($id,',') !== false){
            $ids = explode(',',$id);
            $result = '';
            foreach($ids as $key => $val){
                $model->$action($val);
                $result++;
            }
        }else{
            $result = $model->$action($id);
        }

        return $result;
    }

    public function get_id($id,$model,$action){
        $result = $model->$action($id);
        return $result;
    }

    public function getError(){
        return $this->error;
    }
}