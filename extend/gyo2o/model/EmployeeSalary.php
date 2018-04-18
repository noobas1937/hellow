<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/1 0001
 * Time: 下午 4:50
 */

namespace gyo2o\model;


use gyo2o\BaseModel;

class EmployeeSalary extends BaseModel
{
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

}