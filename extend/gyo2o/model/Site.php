<?php

namespace gyo2o\model;

use gyo2o\BaseModel;
use gyo2o\dao\SiteDao;
use think\Model;

class Site extends BaseModel
{
    

    //按条件获取站点列表
    public function getSites($where,$sort,$order,$offset,$limit){
        $siteDao = new SiteDao();
        $total = $siteDao->getTotal($where,$sort,$order);
        $list = $siteDao->getSites($where,$sort,$order,$offset,$limit);
        if(!empty($list)){
            foreach ($list as &$value){
                $value['city_name'] = get_city_name($value['city_id']);
                $value['rider_name'] = id2name('rider',$value['rider_id']);
            }
        }

        return array('total'=>$total,'rows'=>$list);
    }

    public function getAll(){
        $siteDao = new SiteDao();
        $sites = $siteDao->select();
        return collection($sites)->toArray();
    }






}
