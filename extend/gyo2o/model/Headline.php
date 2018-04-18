<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/30 0030
 * Time: 下午 2:21
 */

namespace gyo2o\model;

use gyo2o\BaseModel;
use gyo2o\dao\HeadlineDao;
use think\Db;

class Headline extends BaseModel
{

    /**
     * 处理头条数据
     * @param $param
     * @return array
     */
    public function get_by_hot($param){
        $HeadlineDao = new HeadlineDao();
        $city_id = isset($param['city_id']) ? $param['city_id'] : $this->city_id;
        $page_size = isset($param['page_size']) ? $param['page_size'] : 5;
        $data =  $HeadlineDao->get_by_hot($city_id,$page_size);
        return $data;
    }

    public function getHeadlines($where,$sort,$order,$offset,$limit){
        $headlineDao = new HeadlineDao();
        $total = $headlineDao->getTotal($where);
        if(empty($total)){
            return ['total'=>0,'rows'=>[]];
        }
        $rows = $headlineDao->getHeadlines($where,$sort,$order,$offset,$limit);
        array_walk($rows,[$this,'addtionDictionaryName']);
        return ['rows'=>$rows,'total'=>$total];

    }

    private function addtionDictionaryName(&$row){
        $row['city_id'] = Db::table('tb_data_dictionary')->where('id',$row['city_id'])->value('name');
        $row['province_id'] = Db::table('tb_data_dictionary')->where('id',$row['province_id'])->value('name');
    }

}