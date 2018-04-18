<?php

namespace gyo2o\model;

use gyo2o\BaseModel;
use gyo2o\dao\CreditsRecordDao;
use gyo2o\dao\EmployeeDao;
use gyo2o\dao\UserAccountDao;
use think\Exception;

class TbRecord extends BaseModel
{
    public function get_list($data,$ids=null){
        $model = new CreditsRecordDao();
        $result = $this->get_base_list($data,$model,'get_list',$ids);
        $count = $this->get_base_count($data,$model,'get_count');
        if($count > 0){
            foreach($result as $k => $v){
                if($v['type'] == 1){
                    $v['type'] = '收入';
                }else{
                    $v['type'] = '支出';
                }
            }
            return ['total'=>$count,'rows'=>$result];
        }else{
            return ['total'=>0,'rows'=>[]];
        }
    }

    public function add($data){
        $model = new CreditsRecordDao();
        try{
            $result = $this->base_add($data,$model,'add');
            if($result){
                $tb_employee = new EmployeeDao();
                if($data['type'] == 1){
                    $tb_employee->insert_points($data['credits'],$data['tb_employee_id']);
                }
                if($data['type'] == 2){
                    $tb_employee->dec_points($data['credits'],$data['tb_employee_id']);
                }
            }
        }catch(Exception $e){
            $result = false;
        }
        return $result;
    }

    public function edit($id,$data){
        $model = new EmployeeDao();
        $result = $this->base_edit($id,$data,$model,'edit');
        return $result;
    }

    public function del($id){
        $model = new EmployeeDao();
        $result = $this->base_del($id,$model,'del');
        return $result;
    }

    public function get_one($id){
        $model = new EmployeeDao();
        $result = $this->get_id($id,$model,'get_id');
        return $result;
    }


}
