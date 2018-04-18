<?php

namespace gyo2o\model;

use gyo2o\BaseModel;
use gyo2o\dao\AdDao;
use gyo2o\dao\attachmentDao;

class Ad extends BaseModel
{
    /**
     * 广告列表数据处理
     * @param $param
     * @return array|false
     */
    public function ad_list($param){
        $city_id = isset($param['city_id']) ? $param['city_id'] : $this->city_id;
        $page_size = isset($param['page_size']) ? $param['page_size'] : 5;
        $ad = new AdDao();
        $result = $ad->get_type($param['type'],$city_id,$page_size);
        if(isset($result['data']) && is_array($result['data'])){
            $att = new attachmentDao();
            foreach($result['data'] as $key => $val){
                $result['data'][$key]['img_url'] = $att->getUrlAttr($val['img_id']);
            }
        }
        return $result;
    }

    public function getAds($where,$sort,$order,$offset,$limit){
        $adDao = new AdDao();
        $total = $adDao->getTotal($where);
        if(empty($total)){
            return ['total'=>0,'rows'=>[]];
        }
        $rows = $adDao->getAds($where,$sort,$order,$offset,$limit);
        $att = new attachmentDao();
        foreach($rows as &$row){
            $row['img_id'] = $att->getUrlAttr($row['img_id']);
        }
        return ['total'=>$total,'rows'=>$rows];
    }

    public function getById($id){
        $adDao = new AdDao();
        $row = $adDao->getById($id);
        if(empty($row)){
            return false;
        }
        $attachementDao = new attachmentDao();
        $row['img_id'] = $attachementDao->getUrlAttr($row['img_id']);
        return $row;
    }

    public function edit(AdDao $ad,$param){
        $domain = \Think\Config::get('qiniu.domain');
        $file = substr(strstr($param['img_id'],$domain),strlen($domain)+1);
        $attachment = new attachmentDao();
        $id = $attachment->where('url',$file)->value('id');
        $param['img_id'] = $id;
        return $ad->save($param);
    }

    public function add($param){
        $domain = \Think\Config::get('qiniu.domain');
        $file = substr(strstr($param['img_id'],$domain),strlen($domain)+1);
        $attachment = new attachmentDao();
        $id = $attachment->where('url',$file)->value('id');
        $param['img_id'] = $id;
        $adDao = new AdDao();
        return $adDao->save($param);
    }

}
