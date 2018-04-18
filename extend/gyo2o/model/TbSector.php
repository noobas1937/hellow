<?php

namespace gyo2o\model;

use gyo2o\BaseModel;
use gyo2o\dao\EnterpriseDao;
use gyo2o\dao\SectorDao;

class TbSector extends BaseModel
{
    public function get_list($data){
        $model = new SectorDao();
        $result = $this->get_base_list($data,$model,'get_list');
        $count = $this->get_base_count($data,$model,'get_count');
        if($count > 0){
            $company = new EnterpriseDao();
            foreach($result as $key => $val){
                $company_info = $company->get_id($val['enterprise_id']);
                $val['enterprise_id'] = $company_info['name'].'('.$company_info['id'].')';
            }
            return ['total'=>$count,'rows'=>$result];
        }else{
            return ['total'=>0,'rows'=>[]];
        }
    }

    public function get_all($pkey_value = null){
        $model = new SectorDao();
        $result = $model->get_all($pkey_value);
        return $result;
    }



    public function get_company_all($enterprise_id){
        $model = new SectorDao();
        $result = $model->get_company_all($enterprise_id);
        return $result;
    }

    public function add($data){
        $model = new SectorDao();

        $result = $this->base_add($data,$model,'add');
        return $result;
    }

    public function edit($id,$data){
        $model = new SectorDao();
        $result = $this->base_edit($id,$data,$model,'edit');
        return $result;
    }

    public function del($id){
        $model = new SectorDao();
        $result = $this->base_del($id,$model,'del');
        return $result;
    }

    public function get_one($id){
        $model = new SectorDao();
        $result = $this->get_id($id,$model,'get_id');
        return $result;
    }


}
