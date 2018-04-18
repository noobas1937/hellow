<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/8 0008
 * Time: 下午 5:27
 */

namespace gyo2o\model;


use gyo2o\BaseModel;
use gyo2o\dao\PackageItemDao;

class Tbpackage extends BaseModel
{
    public function getPackageItem($id){
        $packageItemDao = new PackageItemDao();
        $rows = $packageItemDao->get_by_package($id);
        if(empty($rows)){
            return ['total'=>0,'rows'=>[]];
        }

        return ['total'=>count($rows),'rows'=>$rows];
    }

    public function addItemToPackage($param){
        $packageItemDao = new PackageItemDao();
        return $packageItemDao->save($param);
    }

}