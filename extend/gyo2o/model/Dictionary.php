<?php
namespace gyo2o\model;

use gyo2o\BaseModel;
use gyo2o\dao\DataDictionaryDao;

class Dictionary extends BaseModel
{
    public function get_by_address($res_key)
    {
        $address = new DataDictionaryDao();
        $result = $address->get_by_res_key($res_key);
        return $result;
    }

    public function get_by_area($pid)
    {
        $address = new DataDictionaryDao();
        $result = $address->get_by_pid($pid);
        return $result;
    }

    public function get_id($id)
    {
        $address = new DataDictionaryDao();
        $result = $address->get_by_id($id);
        return $result;
    }
}