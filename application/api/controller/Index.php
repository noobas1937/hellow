<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27 0027
 * Time: 上午 10:29
 */

namespace app\api\controller;


use function GuzzleHttp\Psr7\str;
use gyo2o\dao\PackagedetailDao;
use gyo2o\model\Dishes;
use gyo2o\model\Package;
use gyo2o\model\Packagedetail;
use think\Controller;
use think\Db;

class Index extends Common
{
    public function index(){
        //获取下周套餐信息

        do{
            if(date('w')==1){
                $nex_monday = date('Y-m-d',strtotime('+2 monday',time()));
            }else{
                $nex_monday = date('Y-m-d',strtotime('+1 monday',time()));
            }
            
            $pacakeModel = new Package();
            $package = $pacakeModel->getLastPackage($nex_monday);

            if(empty($package)){
                $this->errstr = '餐品还未发布';
                $this->code = 100001;
                break;
            }
            $package[0]['start_time'] = date('m.d',strtotime($package[0]['start_time']));
            $package[0]['end_time'] = date('m.d',strtotime($package[0]['end_time']));
            return json(['code'=>10000,'status'=>'success','data'=>$package]);
        }while(0);

        return $this->apireturn($package);


    }

    public function package_detail(){
        header('Access-Control-Allow-Origin:*');
        do{
            $list = [];
            $packageid = $this->request->get('id');
            if(empty($packageid)){
                $this->errstr = '缺少ID参数';
                $this->code = 100001;
                break;
            }

            $packagedetailModel = new Packagedetail();
            $list = $packagedetailModel->getPackageDishes($packageid);

            if(empty($list)){
                $this->errstr = 'ID错误';
                $this->code = 100001;
                break;
            }


        }while(0);

        return $this->apireturn($list);
    }

    public function dayDishes(){
        do{
            $result = array();
            $date = $this->request->get('date');
            $dishModel = new Packagedetail();
            $dishes = $dishModel->getDishesByDate($date);
            if(!$dishes){
                $this->errstr = '数据有误';
                $this->code = 100001;
                break;
            }
            $result = $dishes;

        }while(0);
       return $this->apireturn($result);
    }

    public function getAllDetail(){
        $detail = new PackagedetailDao();
        $all = $detail->where('package_id',0)->delete();var_dump($all);die;
        var_dump(collection($all)->toArray());die;
    }

}