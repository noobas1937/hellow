<?php

namespace gyo2o\model;

use gyo2o\BaseModel;
use gyo2o\dao\DishesDao;
use think\Model;

class Dishes extends BaseModel
{

    //按条件获取菜品列表
    public function getDishes($where,$sort,$order,$offset,$limit){
        $siteDao = new DishesDao();
        $total = $siteDao->getTotal($where,$sort,$order);
        $list = $siteDao->getDishes($where,$sort,$order,$offset,$limit);


        return array('total'=>$total,'rows'=>$list);
    }




}
