<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/3 0003
 * Time: 下午 2:43
 */

namespace gyo2o\model;


use gyo2o\BaseModel;
use gyo2o\dao\CredisIncreasementDetailDao;
use gyo2o\dao\CreditsIncreasementDao;
use gyo2o\dao\CreditsRecodDao;
use gyo2o\dao\CreditsRecordDao;
use gyo2o\dao\EmployeeDao;
use gyo2o\dao\LuckyDrawDao;
use think\exception\PDOException;
use think\Exception;

class Salary extends BaseModel
{
    public function creditsIncreasement($params){
        $creditsRecordDao = new CreditsRecodDao();
        $creditsIncreaseDao = new CreditsIncreasementDao();
        //添加流水记录表
        $data['credits'] = $params['credits'];
        $data['create_date'] = date('Y-m-d H:i:s');
        $data['tb_employee_id'] = $params['employee_id'];
        $creditsRecordDao->startTrans();
        $recordid = $creditsRecordDao->insertGetId($data);
        if($params['type'] == 1){
            //工资默认立即生效
            $increasement = ['credits'=>$params['credits'],'employee_id'=>$params['employee_id'],'type'=>1,'unfreeze_time'=>date('Y-m-d'),'remark'=>$params['remark'],'record_id'=>$recordid];
        }elseif($params['type'] == 2){
            //奖金默认人事已确认
            $increasement = ['credits'=>$params['credits'],'employee_id'=>$params['employee_id'],'type'=>2,'unfreeze_time'=>$params['unfreeze_time'],'remark'=>$params['remark'],'record_id'=>$recordid,'isconfirm'=>2];
        }
        $increasementid = $creditsIncreaseDao->insertGetId($increasement);
        if($increasementid&&$recordid){
            $creditsRecordDao->commit();
            return true;
        }else{
            $creditsRecordDao->rollback();
            return false;
        }
    }

    public function getSalaryWaste($where, $sort, $order, $offset, $limit,$ids=0){
        $creditsRecordDao = new CreditsRecodDao();
        $total = $creditsRecordDao->countByCondition($where,$ids);
        $record = $creditsRecordDao->getByCondition($where, $sort, $order, $offset, $limit,$ids);
        array_walk($record,[$this,'addtionRecord']);
        return ['total'=>$total,'rows'=>$record];
    }

    public function addtionRecord(&$row){
        $employeeDao = model('\\gyo2o\\dao\\EmployeeDao');
        $employee = $employeeDao->find($row['tb_employee_id']);
        $row['name'] = $employee['name'];
        $row['idcard'] = $employee['idcard'];
        $row['contact_moblie'] = $employee['contact_moblie'];
        $creditsIncreasementDao = model('\\gyo2o\\dao\\CreditsIncreasementDao');
        $increasement = $creditsIncreasementDao->getByRecordid($row['id']);
        if(!empty($increasement)){
            //收入表
            $row['type'] = $increasement['type'] ;
            $row['unfreeze_time'] = $increasement['unfreeze_time'];
            $row['remark'] = $increasement['remark'];
            $row['grand_type'] = $increasement['grand_type'];
            $row['isconfirm'] = $increasement['isconfirm'];
            $row['descript'] = $increasement['remark'];
        }elseif($row['credits']<0){
            $creditsRecduceDao = model('\\gyo2o\\dao\\CreditsReduceDao');
            $reduce = $creditsRecduceDao->getByRecordid($row['id']);
            if($reduce['reduce_type']){
                $luckyDraw = new LuckyDrawDao();
                $lucky = $luckyDraw->getById($reduce['reduce_type']);
                $row['descript'] = $lucky['title'];
                $row['remark'] = $lucky['title'];
            }else{
                $row['descript'] = '兑换余额';
                $row['remark'] = '兑换余额';
            }
        }
    }

    public function getOne($id){
        $creditsRecordDao = new CreditsRecodDao();
        $record = $creditsRecordDao->find($id);
        if($record['credits']<0){
            return false;
        }
        $this->addtionRecord($record);
        if($record['isconfirm'] >0 && strtotime($record['unfreeze_time'])<=time()){

            //已确认已解冻不可以修改
            return false;
        }
        return $record;
    }

    public function edit($param){
        $creditsRecordDao = new CreditsRecodDao();
        $creditsIncreaseDao = new CreditsIncreasementDao();
        $creditsRecord = $creditsRecordDao->find($param['id']);
        $creditsIncrease = $creditsIncreaseDao->getByRecordid($param['id']);
        $creditsRecordDao->startTrans();
        $recordResult = $creditsRecord->save(['credits'=>$param['credits']]);

        if($param['type'] == 1){
            //工资默认立即生效
            $increasement = ['credits'=>$param['credits'],'type'=>1,'unfreeze_time'=>date('Y-m-d'),'remark'=>$param['remark'],'isconfirm'=>0];
        }elseif($param['type'] == 2){
            //奖金默认人事已确认
            $increasement = ['credits'=>$param['credits'],'type'=>2,'unfreeze_time'=>$param['unfreeze_time'],'remark'=>$param['remark'],'isconfirm'=>2];
        }
        $increasementResult = $creditsIncrease->save($increasement);
        if($recordResult!==false && $increasementResult !== false){
            $creditsRecordDao->commit();
            return true;
        }else{
            $creditsRecordDao->rollback();
            return false;
        }
    }

    public function hrConfirm($id){
        $creditsIncreasementDao = new CreditsIncreasementDao();
        return $creditsIncreasementDao->hrConfirm($id);
    }

    public function importExcel($path,$year,$month){
        set_time_limit(0);
        \Think\Loader::import('phpexcel.PHPExcel');
        try{
            $excel = \PHPExcel_IOFactory::load($path);
        }catch (Exception $exception){
            $this->error = '格式不正确';
            return false;
        }

        $currentSheet = $excel->getSheet(0);
        $break = 0;
        $highestcolumn = $currentSheet->getHighestColumn();
        for ($i = ord('A');$i<= ord('Z');$i++){
            $headarr[] = chr($i);
            if(chr($i) == $highestcolumn){
                $break = 1;
                break;
            }
        }
        if(!$break){
            for($i = ord('A');$i<= ord('Z');$i++){
                for($j = ord('A');$j<= ord('Z');$j++){
                    $headarr[] = chr($i).chr($j);
                    if(chr($i).chr($j) == $highestcolumn){
                        $break = 1;
                        break;
                    }
                }
                if($break){
                    break;
                }
            }
        }

        foreach ($headarr as $value){
            $headtitle[$value] = trim($currentSheet->getCell($value.'1')->getValue());
        }

        $salaryForm = new SalaryFormHead();
        foreach ($headtitle as $key =>$value){
            if(isset($salaryForm->head[$value])){
                $coumn[$key] = $salaryForm->head[$value];
                if($value == '身份证号'){
                    $idcardindex = $salaryForm->head[$value];
                }
                if($value == '实发工资'){
                    $creditsindex = $salaryForm->head[$value];
                }
            }
        }

        $allRow = $currentSheet->getHighestDataRow();

        $data = array();
        $i = 0;
        $creditsRecordDao = new CreditsRecodDao();
        $creditIncreasementDao = new CreditsIncreasementDao();
        $creditIncreasementDetailDao = new CredisIncreasementDetailDao();
        $creditsRecordDao->startTrans();
        for ($currentRow = 2; $currentRow <= $allRow; $currentRow ++) {
            // 循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始

            foreach ($coumn as $key=>$value){
                $data[$i][$value] = trim($currentSheet->getCell($key.$currentRow)->getValue());// 读取到的数据，保存到数组$arr中
            }

            $employeeDao = new EmployeeDao();
            $employee = $employeeDao->getByIdcard(trim($data[$i][$idcardindex]));
            if(empty($employee)){
                $this->error = $data[$i]['B'].":".$data[$i]['F'] ."不是系统员工";
                return false;
            }

            $recorddata = ['credits'=>$data[$i][$creditsindex],'tb_employee_id'=>$employee['id'],'create_date'=>date('Y-m-d H:i:s')];
//            $year = date('Y');
//            $month = date('m',strtotime('-m'));
            $cdata['employee_id'] = $employee['id'];
            $cdata['credits'] = $data[$i][$creditsindex];
            $cdata['type'] = 1;
            $cdata['unfreeze_time'] = date('Y-m-d');
//            $cdata['isconfirm'] = 2;
            $data[$i]['tb_year'] = $year;
            $data[$i]['tb_month'] = $month;
            $cdata['remark'] = $year.'年'.$month.'月工资';
            $detail = $creditIncreasementDetailDao->getByIdcard($data[$i][$idcardindex],$year,$month);
            if(empty($detail)){
                //新增
                $recorid = $creditsRecordDao->insertGetId($recorddata);

                $cdata['record_id'] = $recorid;
                if($recorid){
                    $return = $creditIncreasementDao->insert($cdata);
                    $data[$i]['record_id'] = $recorid;
                    $return1 = $creditIncreasementDetailDao->insert($data[$i]);
                    if($return===false || $return1===false){
                        $creditsRecordDao->rollback();
                        $this->error = '导入失败稍后再试';
                        return false;
                    }
                }else{
                    $creditsRecordDao->rollback();
                    $this->error = '导入失败稍后再试';
                    return false;
                }
            }else{
                //修改

                $recordMode = $creditsRecordDao->find($detail['record_id']);
//                if($data[$i]['B']=='贺天龙'){
//                    var_dump($creditsRecordDao->where(['id'=>$detail['record_id']])->update($recorddata),$creditsRecordDao->getLastSql(),$data[$i],$detail['record_id']);die;
//                }

                $increaseModel = $creditIncreasementDao->getByRecordid($detail['record_id']);
                $result1 = $creditsRecordDao->where(['id'=>$detail['record_id']])->update($recorddata);
                $result2 = $creditIncreasementDetailDao->where(['id'=>$detail['id']])->update($data[$i]);
                $result3 = $creditIncreasementDao->where(['record_id'=>$detail['record_id']])->update($cdata);
                if($result1 === false || $result2 === false || $result3 ===false){

                    $creditIncreasementDao->rollback();
                    $this->error = '导入失败稍后再试';
                    return false;
                }

            }


            unset($data[$i]);unset($cdata);unset($recorddata);
            $i++;
        }

        $creditsRecordDao->commit();


        return true;
    }

    //单条工资的明细
    public function getSingleSalaryDetail($recordid){
        $creditsIncreasementDetailDao = new CredisIncreasementDetailDao();
        $creditsIncreasementDao = new CreditsIncreasementDao();
        $record = $creditsIncreasementDao->getByRecordid($recordid);
        $detail = $creditsIncreasementDetailDao->getByRecordid($recordid)->toArray();
        $salryFormHead = new SalaryFormHead();
        $data = [];
        $head = array_flip($salryFormHead->head);
        foreach ($detail as $key => $value){
            if(!empty($value) || $value==='0'){
                if(!empty($head[$key])&&$key!='B'&&$key!='F'){
                    $data[] = ['key'=>$head[$key],'value'=>$value];
                }
            }
        }
        $emploeeDao = new EmployeeDao();
        $employee = $emploeeDao->find($record['employee_id']);
        $result['detail'] = $data;
        $result['id'] = $record['record_id'];
        $result['head'] = $employee;
        $result['tail'] = $record;

        return $result;

    }

    //导入奖励
    public function importPrize($path){
        set_time_limit(0);
        \Think\Loader::import('phpexcel.PHPExcel');
        try{
            $excel = \PHPExcel_IOFactory::load($path);
        }catch (Exception $exception){
            $this->error = '格式不正确';
            return false;
        }

        $currentSheet = $excel->getSheet(0);
        //读取表头奖励项
        $highestcolumn = $currentSheet->getHighestColumn();
        for ($i = ord('C');$i<= ord($highestcolumn);$i++){
            $headarr[chr($i)] = trim($currentSheet->getCell(chr($i).'1'));
        }
        unset($i);
        //var_dump($headarr);die;
        $allRow = $currentSheet->getHighestDataRow();
        //读取数据
        $employeeDao = new EmployeeDao();
        $creditsRecordDao = new CreditsRecordDao();
        $creditsIncreasementDao = new CreditsIncreasementDao();
        $creditsRecordDao->startTrans();
        for($currentRow=2;$currentRow<=$allRow;$currentRow++){
            $idcard = trim($currentSheet->getCell('B'.$currentRow));
            $employee = $employeeDao->getByIdcard($idcard);
            if(empty($employee)){
                $this->error = $currentSheet->getCell('A'.$currentRow).":".$idcard ."不是系统员工";
                return false;
            }
            //读取奖励项对应的奖金
            foreach ($headarr as $key=>$value){
                $prizeValue = trim($currentSheet->getCell($key.$currentRow));
                if(empty($prizeValue)){
                    //为空忽略
                    continue;
                }
                $recordData = ['tb_employee_id'=>$employee['id'],'credits'=>$prizeValue,'create_date'=>date('Y-m-d H:i:s')];

                $cdata['employee_id'] = $employee['id'];
                $cdata['credits'] = $prizeValue;
                $cdata['type'] = 2;
                $cdata['unfreeze_time'] = date('Y-m-d');
                $cdata['remark'] = $value;
                $cdata['isconfirm'] = 2;
                //判断是否已有奖励项纪录
                $increasement = $creditsIncreasementDao->where(['employee_id'=>$employee['id'],'remark'=>$value])->find();
                if(empty($increasement)){
                    //新增
                    $recordid = $creditsRecordDao->insertGetId($recordData);
                    if($recordid){
                        $cdata['record_id'] = $recordid;
                        $return = $creditsIncreasementDao->insert($cdata);

                        if($return===false ){
                            $creditsRecordDao->rollback();
                            $this->error = '导入失败稍后再试';
                            return false;
                        }
                    }else{
                        $creditsRecordDao->rollback();
                        $this->error = '导入失败稍后再试';
                        return false;
                    }
                }else{
                    //修改记录
                    $result1 = $creditsRecordDao->where(['id'=>$increasement['record_id']])->update($recordData);
                    $result2 = $creditsIncreasementDao->where(['record_id'=>$increasement['record_id']])->update($cdata);
                    if($result1 === false || $result2 === false ){
                        $creditsRecordDao->rollback();
                        $this->error = '导入失败稍后再试';
                        return false;
                    }
                }

                unset($recordData);unset($cdata);

            }
            unset($employee);unset($idcard);

        }
        $creditsRecordDao->commit();
        return true;
    }

    public function getSingleDetaliAll($recordid){
        $creditsIncreasementDetail = new CredisIncreasementDetailDao();
        $detail = $creditsIncreasementDetail->getByRecordid($recordid);
        if(!empty($detail)){
            $result= ['total'=>1,'rows'=>[$detail]];
            return $result;
        }else{
            $this->error = '该条记录没有工资明细';
            return false;
        }
    }

}