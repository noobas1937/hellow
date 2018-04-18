<?php

namespace gyo2o\model;

use gyo2o\BaseModel;
use gyo2o\dao\DishesDao;
use gyo2o\dao\OrderDao;
use gyo2o\dao\PackagedetailDao;
use gyo2o\dao\RiderDao;
use gyo2o\dao\SiteDao;
use think\Model;
use think\Session;

class Rider extends BaseModel
{


    //按月获取骑手订单
    public function riderorder($riderid,$month){
        //获取员工入/离职职日期
        $riderDao = new RiderDao();
        $time = $riderDao->getRider($riderid);
        $jtime = $time['jointime'];
        $joinmonth = date('Y-m',$jtime);

        $leavetime = $time['leavetime'];


        $joindate = date('Y-m-d',$jtime);
        $where = "DATE_FORMAT(date,'%Y-%m')='$month' and date >='$joindate'";

//            $where = "DATE_FORMAT(date,'%Y-%m')='$month'";


        if(!empty($leavetime)){
            $leavedate = date('Y-m-d',$leavetime);
            $where .= " and date < '$leavedate'";
        }

        $dishesDao = new PackagedetailDao();
        $dishes = $dishesDao->getDisheBymonty($where);
        if(empty($dishes)){
            $list = [];
            $total = 0;
        }else{

            //骑手点餐信息
            $orderDao = new OrderDao();
            $order = $orderDao->getOrderByRider($where." and rider_id=$riderid");

            $total = count($dishes);
            $rorder = array();
            foreach ($order as $val){
                $rorder[$val['date']] = $val;
            }

            $list = array();
            $weekstr = ['0'=>'周日','1'=>'周一','2'=>'周二','3'=>'周三','4'=>'周四','5'=>'周五','6'=>'周六'];
            foreach ($dishes as $value){
                $weekday = date('w',strtotime($value['date']));
                $weekday = $weekstr[$weekday];
                $day = date('d',strtotime($value['date']));
                $monthday = date('m-d',strtotime($value['date']));

                //判断是否可以修改
                $nex_monday = date('w')==1?strtotime('+2 monday',time()):strtotime('+1 monday',time());
                if(strtotime($value['date'])>=$nex_monday && date('w')<=5){
                    if(date('w')==5){
                        if(date('G')<=12){
                            $editable = 1;
                        }else{
                            $editable = 0;
                        }
                    }else{
                        $editable = 1;
                    }

                }else{
                    $editable = 0;
                }

                if(isset($rorder[$value['date']])){
                    //有订餐信息
                    if($rorder[$value['date']]['dishes_type'] == 'A'){

                        $cname = id2name('dishes',$value['dishes_id']);
                        $cimage = dishesimage($value['dishes_id']);

                    }elseif ($rorder[$value['date']]['dishes_type'] == 'B') {

                        $cname = id2name('dishes', $value['dishes1_id']);
                        $cimage = dishesimage($value['dishes1_id']);

                    }elseif ($rorder[$value['date']]['dishes_type'] == 'C'){

                        $cname = id2name('dishes', $value['dishes2_id']);
                        $cimage = dishesimage($value['dishes2_id']);

                    } elseif ($rorder[$value['date']]['dishes_type'] == 'Z') {

                        //取消
                        continue;
                    }
                    $status = $rorder[$value['date']]['order_status'];
                    if(strtotime($value['date'])+3600*24<=time()){
                        $status = '已领';
                    }

                    $list[] = ['editable'=>$editable,'image'=>$cimage,'weekday'=>$weekday,'day'=>$day,'monthday'=>$monthday,'date'=>$value['date'],'cname'=>$cname,'status'=>$status];
                }else{
                    $cname = $cname = id2name('dishes', $value['dishes_id']);
                    $cimage = dishesimage($value['dishes_id']);
                    $status = '待领';
                    if(strtotime($value['date'])+3600*24<=time()){
                        $status = '已领';
                    }
                    $list[] = ['editable'=>0,'image'=>$cimage,'weekday'=>$weekday,'day'=>$day,'monthday'=>$monthday,'date'=>$value['date'],'cname'=>$cname,'status'=>$status];
                }

            }

        }

        return ['list'=>$list,'total'=>$total];
    }

    public function getRiders($where,$sort,$order,$offset,$limit){
        $riderDao = new RiderDao();
        $siteid = Session::get('admin.blesite_id')?Session::get('admin.blesite_id'):0;
        $total = $riderDao->getTotal($where,$sort,$order,$siteid);
        $list = $riderDao->getRiders($where,$sort,$order,$offset,$limit,$siteid);
        if(!empty($list)){
            foreach ($list as &$value){
                $value['city_name'] = get_city_name($value['city_id']);
                $value['site_name'] = get_site_name($value['blesite_id']);
            }
        }
        return array('total'=>$total,'rows'=>$list);
    }

    //通过身份证认证骑手
    public function riderAuth($userid,$idcard){
        $riderDao = new RiderDao();
        $riderInfo = $riderDao->getByIdcard($idcard);
        if(empty($riderInfo)){
            return false;
        }
        if(!empty($riderInfo['user_id'])&&$riderInfo['user_id']!=$userid){
            return ['status'=>false,'msg'=>'已认证过不可重复认证'];
        }
        //有身份信息录入openid
        $riderInfo->user_id = $userid;
        if($riderInfo->save()!==false){
            return $riderInfo->toArray();
        }
        return false;

    }

    //通过openid获取骑手信息
    public function getRiderByOpenid($openid){
        $riderDao = new RiderDao();
        $rider = $riderDao->getRiderByOpenid($openid);
        if(empty($rider)){
            return false;
        }

        return $rider->toArray();
    }

    public function setSite($riderid,$siteid){
        $riderDao = new RiderDao();
        $rider = $riderDao->where("Id=$riderid")->find();
        if(empty($rider)){
            return false;
        }
        $rider->site_id = $siteid;
        return $rider->save();

    }

    //通过userid获取骑手信息
    public function getRiderByUserid($userid){
        $riderDao = new RiderDao();
        $rider = $riderDao->getRiderByUserid($userid);
        if(empty($rider)){
            return false;
        }

        $rider =  $rider->toArray();
        $rider['site_name'] = get_site_name($rider['blesite_id']);
        return $rider;
    }

    //导入excel表格
    public function importExcel($path){
        \Think\Loader::import('phpexcel.PHPExcel');
        $excel = \PHPExcel_IOFactory::load($path);
        $currentSheet = $excel->getSheet(0);

        $allRow = $currentSheet->getHighestRow();
        $coumn = ['B'=>'name','C'=>'sex','D'=>'type','E'=>'city_id','F'=>'site_id','L'=>'idcard','G'=>'jointime','O'=>'mobile'];
        $data = array();

        $i = 0;
        for ($currentRow = 2; $currentRow <= $allRow; $currentRow ++) {
            // 循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始

            foreach ($coumn as $key=>$value){
                $data[$i][$value] = $currentSheet->getCell($key.$currentRow)->getValue();// 读取到的数据，保存到数组$arr中
                if($value=='jointime'){

                    $data[$i][$value] = strtotime(excelTime($data[$i][$value]));
                }
                if($value == 'site_id'){
                    $data[$i][$value] = name2id('site',$data[$i][$value])?name2id('site',$data[$i][$value]):0;
                    $data[$i]['blesite_id'] = $data[$i][$value];
                }
                if($value == 'city_id'){
                    $data[$i][$value] = name2id('city',$data[$i][$value])?name2id('city',$data[$i][$value]):0;
                }

                $data[$i]['createtime'] = time();
            }
            $i++;
        }

        $riderDao = new \gyo2o\dao\RiderDao();
        $result = $riderDao->saveAll($data,false);
        return $result;
    }

    //判断骑手是否离职
    public function isLeave($riderid){
        $riderDao = new RiderDao();
        $rider = $riderDao->getRider($riderid);
        if(empty($rider)){
            return true;
        }else{
            if($rider['type']=='已离职'||!empty($rider['leavetime'])){
                return true;
            }
        }
        return false;
    }
}
