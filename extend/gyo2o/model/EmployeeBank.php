<?php

namespace gyo2o\model;

use gyo2o\BaseModel;
use gyo2o\dao\EmployeeBankDao;
use think\exception\PDOException;

class EmployeeBank extends BaseModel
{
    public function add_bank($param){
        $bank = new EmployeeBankDao();
        try {
            $result = $bank->add_bank($param);
            return $result;
        }catch(PDOException $e){
            return $e->getMessage();
        }
        return false;
    }
}