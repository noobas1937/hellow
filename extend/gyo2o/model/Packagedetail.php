<?php

namespace gyo2o\model;

use gyo2o\BaseModel;
use gyo2o\dao\DishesDao;
use gyo2o\dao\PackageDao;
use gyo2o\dao\PackagedetailDao;
use think\Model;

class Packagedetail extends BaseModel
{


    //按ID获取套餐下菜品列表(后台列表用)
    public function getPackagedetail($id){
        $packagedeailDao = new PackagedetailDao();
        $total = $packagedeailDao->getCountByPackageId($id);

        $list = $packagedeailDao->getPackageDetail($id);
        foreach ($list as &$value){
            $value['dishes_name'] = id2name('dishes',$value['dishes_id']);
            $value['dishes1_name'] = id2name('dishes',$value['dishes1_id']);
            $value['dishes2_name'] = id2name('dishes',$value['dishes2_id']);
            $value['package_name'] = id2name('package',$value['package_id']);
            $value['week_day'] = date('w',strtotime($value['date']))?date('w',strtotime($value['date'])):7;
        }
        return array("total" => $total, "rows" => $list);

    }

    //根据ID获取套餐下菜品列表（Api用)
    public function getPackageDishes($packageid){
        $packagedetailDao = new packagedetailDao();
        $list = $packagedetailDao->getPackagedetail($packageid);
        $list = collection($list)->toArray();

        //获取套餐菜品
        $dishesDao = new DishesDao();
        $dishes = $dishesDao->getAllDishes();

        $dlist = array();
        foreach ($dishes as $dish){
            $dlist[$dish['Id']] = $dish;
        }

        //套餐餐品添加餐品信息
        $weekstr = ['0'=>'周日','1'=>'周一','2'=>'周二','3'=>'周三','4'=>'周四','5'=>'周五','6'=>'周六'];
        foreach ($list as &$value){
            $weekday = date('w',strtotime($value['date']));
            //餐品A
            if(!empty($value['dishes_id'])&&$dlist[$value['dishes_id']]['dishes_status']=='正常'){
                $value['dishes_type']['A']['name'] = $dlist[$value['dishes_id']]['name'];
                $value['dishes_type']['A']['price'] = $dlist[$value['dishes_id']]['dishes_price'];
                $value['dishes_type']['A']['image'] = $dlist[$value['dishes_id']]['dishes_image'];
                $value['dishes_type']['A']['id'] = $value['dishes_id'];
                $value['dishes_type']['A']['lable'] = 'A-'.$value['date'];
            }


            //餐品B
            if(!empty($value['dishes1_id'])&&$dlist[$value['dishes1_id']]['dishes_status']=='正常'){
                $value['dishes_type']['B']['name'] = $dlist[$value['dishes1_id']]['name'];
                $value['dishes_type']['B']['price'] = $dlist[$value['dishes1_id']]['dishes_price'];
                $value['dishes_type']['B']['image'] = $dlist[$value['dishes1_id']]['dishes_image'];
                $value['dishes_type']['B']['id'] = $value['dishes1_id'];
                $value['dishes_type']['B']['lable'] = 'B-'.$value['date'];
            }


            //餐品C
            if(!empty($value['dishes2_id'])&&$dlist[$value['dishes2_id']]['dishes_status']=='正常'){
                $value['dishes_type']['C']['name'] = $dlist[$value['dishes2_id']]['name'];
                $value['dishes_type']['C']['price'] = $dlist[$value['dishes2_id']]['dishes_price'];
                $value['dishes_type']['C']['image'] = $dlist[$value['dishes2_id']]['dishes_image'];
                $value['dishes_type']['C']['id'] = $value['dishes2_id'];
                $value['dishes_type']['C']['lable'] = 'C-'.$value['date'];
            }


            $value['weekday'] = $weekstr[$weekday];
            $value['formate_date'] = date('m/d',strtotime($value['date']));

        }

        return $list;
    }


    //按日期获取当天菜品
    public function getDishesByDate($date){
        $packageDetailDao = new PackagedetailDao();
        $dishes = $packageDetailDao->getDishesByDate($date);
        if(empty($dishes)){
            return false;
        }

        $dishesDao = new DishesDao();

        $names['A'] = $dishesDao->getDishesName($dishes[0]['dishes_id']);
        $names['B'] = $dishesDao->getDishesName($dishes[0]['dishes1_id']);
        if(!empty($dishes[0]['dishes2_id'])){
            $names['C'] = $dishesDao->getDishesName($dishes[0]['dishes2_id']);
        }

        $image['A'] = dishesprice($dishes[0]['dishes_id']);
        $image['B'] = dishesprice($dishes[0]['dishes1_id']);
        if(!empty($dishes[0]['dishes2_id'])) {
            $image['C'] = dishesprice($dishes[0]['dishes2_id']);

        }
        $temp = array();
        foreach ($names as $k=>$value){
            $temp[] = array('name'=>$value,'pirce'=>$image[$k],'dishes_type'=>$k,'date'=>$date);
        }
        return $temp;

    }

    //校验套餐下每日餐品时间
    public function checkDate($date,$packageId,$mehtod){
        $package = new PackageDao();
        $packageInfo = $package->where('Id',$packageId)->find();
        $datetime = strtotime($date);
        $starttime = strtotime($packageInfo['start_time']);
        $endtime = strtotime($packageInfo['end_time']);
        if($datetime<$starttime||$datetime>$endtime){
            return ['status'=>false,'msg'=>'日期不在套餐期限内'];
        }

        //套餐下日期重复检查
        $packagedetailDao = new PackagedetailDao();

        if($mehtod == 'add'){
            $detail = $packagedetailDao->where('date',$date)->find();
            if($detail){
                return ['status'=>false,'msg'=>'日期重复'];
            }
        }elseif($mehtod =='edit'){
            $id = input('ids');
            $detail = $packagedetailDao->where('Id',$id)->find();
            if($detail && $detail['date']!=$date){
                return ['status'=>false,'msg'=>'日期重复'];
            }
        }


        return true;
    }

}
