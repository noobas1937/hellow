<?php

namespace gyo2o\model;

use gyo2o\BaseModel;
use gyo2o\dao\EnterpriseDao;

class Enterprise extends BaseModel
{
    public function get_list($data){
        $model = new EnterpriseDao();
        $result = $this->get_base_list($data,$model,'get_list');
        $count = $this->get_base_count($data,$model,'get_count');
        if($count > 0){
            return ['total'=>$count,'rows'=>$result];
        }else{
            return ['total'=>0,'rows'=>[]];
        }
    }

    public function get_all(){
        $model = new EnterpriseDao();
        return $model->get_all();
    }

    public function add($data){
        $model = new EnterpriseDao();
        $result = $this->base_add($data,$model,'add');
        return $result;
    }

    public function edit($id,$data){
        $model = new EnterpriseDao();
        $result = $this->base_edit($id,$data,$model,'edit');
        return $result;
    }

    public function del($id){
        $model = new EnterpriseDao();
        $result = $this->base_del($id,$model,'del');
        return $result;
    }

    public function get_one($id){
        $model = new EnterpriseDao();
        $result = $this->get_id($id,$model,'get_id');
        return $result;
    }


}
