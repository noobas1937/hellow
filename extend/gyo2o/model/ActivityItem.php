<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/11 0011
 * Time: 下午 4:15
 */

namespace gyo2o\model;


use gyo2o\BaseModel;
use gyo2o\dao\ActivityItemDao;
use gyo2o\dao\ItemDao;

class ActivityItem extends BaseModel
{
    public function getActivityItem($activityId){
        $activityItemDao = new ActivityItemDao();
        $rows = $activityItemDao->getByActivityId($activityId);
        if(empty($rows)){
            return ['total'=>0,'rows'=>$rows];
        }
        array_walk($rows,[$this,'addtionItemTitle']);
        return ['total'=>count($rows),'rows'=>$rows];

    }

    private function addtionItemTitle(&$row){
        $itemDao = new ItemDao();
        $item = $itemDao->getById($row['item_id']);
        $row['item_title'] = $item?$item['title']:'';
    }

    public function addItemToActivity($param){
        $activityItemDao = new ActivityItemDao();
        return $activityItemDao->save($param);
    }
}