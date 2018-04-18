<?php

namespace gyo2o\model;

use gyo2o\BaseModel;
use gyo2o\dao\PackageDao;
use gyo2o\dao\PackagedetailDao;
use think\Model;

class Package extends BaseModel
{

    //获取套餐列表
    public function getPackages($where,$sort,$order,$offset,$limit){

        $packageDao = new \gyo2o\dao\PackageDao();
        $total = $packageDao->getCount($where,$sort,$order);


        $list = $packageDao->getPackages($where,$sort,$order,$offset,$limit);
        if(!empty($list)){
            foreach ($list as &$value){
                $value['admin_name'] = adminid2name($value['admin_id']);
            }
        }
        return array("total" => $total, "rows" => $list);

    }

    //获取下周套餐
    public function getLastPackage($nex_monday){
        $packageDao = new PackageDao();
        $package = $packageDao->getLastPackage($nex_monday);
        return $package;
    }

    //套餐编辑时校验
    public function editCheck($id,$startTime,$endTime){
        $packageDao = new PackageDao();
        $row = $packageDao->get($id);
        //时间被修改
        if($row['start_time'] != $startTime){
            //当前套餐下有餐品
            $packagedetailDao = new \gyo2o\dao\PackagedetailDao();
            $number = $packagedetailDao->where('package_id',$id)->count();
            if($number){
                return ['status'=>false,'msg'=>'当前套餐下有餐品'];
            }

            //避免起止时间重复
            return $this->addCheck($startTime);
        }

        return true;

    }

    //添加是校验
    public function addCheck($startTime){
        $packageDao = new PackageDao();
        $row = $packageDao->where('start_time',$startTime)->count();
        if($row){
            //同一时间段已存在，不能重复添加
            return false;
        }
        return true;
    }

    //检查套餐下是否有餐品
    public function isEmpty($id){
        $packageDetailDao = new PackagedetailDao();
        $number = $packageDetailDao->where('package_id',$id)->count();
        return empty($number);
    }
}
