<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27 0027
 * Time: 下午 1:45
 */

namespace gyo2o\model;


use gyo2o\BaseModel;
use gyo2o\dao\LuckyDrawAwardDao;

class LuckyDrawAward extends BaseModel
{
    public function getAwards($luckyDrawid){
        $LuckyDrawAwardDao = new LuckyDrawAwardDao();
        $rows = $LuckyDrawAwardDao->getByLuckyDrawId($luckyDrawid);
        if($rows){
            return ['total'=>count($rows),'rows'=>$rows];
        }

        return ['total'=>0,'rows'=>[]];
    }

    public function addAward($data){
        $LuckyDrawAwardDao = new LuckyDrawAwardDao();
        return $LuckyDrawAwardDao->save($data);
    }
}