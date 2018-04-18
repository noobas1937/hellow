<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27 0027
 * Time: 下午 3:10
 */

namespace gyo2o\model;


use function fast\e;
use gyo2o\BaseModel;
use gyo2o\dao\CreditsIncreasementDao;
use gyo2o\dao\CreditsRecodDao;
use gyo2o\dao\CreditsRecordDao;
use gyo2o\dao\CreditsReduceDao;
use gyo2o\dao\EmployeeActivityDao;
use gyo2o\dao\EmployeeDao;
use gyo2o\dao\EmployeeInnerDao;
use gyo2o\dao\EmployeeInnerWinning;
use gyo2o\dao\EmployeeInnerWinningDao;
use gyo2o\dao\EmployeeOuterWinningDao;
use gyo2o\dao\LuckyDrawAwardDao;
use gyo2o\dao\LuckyDrawDao;
use gyo2o\dao\LuckyDrawRecordDao;
use gyo2o\dao\LuckyTicketDao;
use gyo2o\dao\LuckyTicketRecordDao;
use gyo2o\dao\NewYearDrawTimeDao;

use think\Db;
use think\exception\PDOException;

class LuckyDraw extends BaseModel
{
    //获取活动奖品信息
    public function getAwardByLuckyDrawId($luckyDrawId)
    {
        $luckyDrawAwardDao = new LuckyDrawAwardDao();
        $awards = $luckyDrawAwardDao->getByLuckyDrawId($luckyDrawId);
        if (empty($awards)) {
            return false;
        }
        $reult = $this->process($awards);
        return $reult;
    }

    //整理活动信息
    protected function process($awards)
    {
        $probabilityCount = 0;
        foreach ($awards as $award) {
            $drawInfo[$award['id']] = [
                'id' => $award['id'],
                'prize' => $award['name'],
                'v' => $award['probability'],
                'prize_info' => $award['name'],
                'msg' => '恭喜您！抽中' . $award['level'] . ' ' . $award['name'] . '（您可以在“查看我的奖品”中查看奖品情况）'
            ];
            $probabilityCount += $award['probability'];
        }

        if ($probabilityCount < 100) {
            $drawInfo[0] = [
                'id' => 0,
                'prize' => '谢谢参与',
                'v' => 100 - $probabilityCount,
                'prize_info' => '谢谢参与',
                'msg' => '谢谢参与，下次还有机会'
            ];
        }

        return $drawInfo;
    }

    /**
     * 抽奖流程
     * @author liuwen
     * @param $luckydrawId
     * @param $user_id
     * @param $isticket
     * @return bool
     */
    public function luckyDrawExt($draw, $emoplyee)
    {
        $data = ['id' => 0, 'prize' => '谢谢参与', 'v' => 90, 'prize_info' => '没有中奖', 'msg' => ''];
        // 判断是否在活动时间内
        $start_date = strtotime($draw['start_date']) - 1000000000;
        $end_date = strtotime($draw['end_date']) - 1000000000;
        $current_time = time() - 1000000000;

        if ($current_time < $start_date) {
            $data['msg'] = "暂无活动";
            return $data;
        }

        //是否是获取场外员工名单，
        //$emoplyee['contact_moblie'] = '15717193930';
        //$outer_winning = Db::query('select * FROM tb_employee_outer_winning where mobile = ' . $emoplyee['contact_moblie']);
        //0 < sizeof($outer_winning) &&
        if (0 < rand(0, 1)) {
            $luckyDrawDao = new LuckyDrawDao();
            $luckyDrawDao->startTrans();
            //获取同类型活动所有id
            $sqlstr = 'select `id` from tb_lucky_draw where type = %s';
            $sqlstr = sprintf($sqlstr, $draw['type']);
            $award['ids']['all'] = Db::query($sqlstr);

            //获取同类型活动过期的id
            $sqlstr = 'select `id` from tb_lucky_draw where type = %s and UNIX_TIMESTAMP(`end_date`) < %s';
            $sqlstr = sprintf($sqlstr, $draw['type'], time());
            $award['ids']['expire'] = Db::query($sqlstr);

            $_award_count = intval(sizeof($award['ids']['all']));
            $_award_expire = intval(sizeof($award['ids']['expire']));

            //var_dump('_award_count',$_award_count);
            //var_dump('_award_expire',$_award_expire);

            $sqlstr = 'select award_id,count(*) as _count from tb_lucky_draw_record where lucky_draw_id in (' . $sqlstr . ') group by award_id';
            $award['ids']['award'] = Db::query($sqlstr);
            $award['record'] = [];
            foreach ($award['ids']['award'] as $_val) {
                if ($_val['award_id']) $award['record'][$_val['award_id']] = $_val['_count'];
            }
            //var_dump('record',$award['record']);


            $award['record'][1] = (isset($award['record'][1])) ? $award['record'][1] : 0;
            $award['record'][2] = (isset($award['record'][2])) ? $award['record'][2] : 0;
            $award['record'][3] = (isset($award['record'][3])) ? $award['record'][3] : 0;
            $award['record'][4] = (isset($award['record'][4])) ? $award['record'][4] : 0;
            //var_dump('record',$award['record']);


            $award['limit'] = [];
            $award['limit'][1] = 260 - ($_award_count - $_award_expire - 1) * intval(260 / $_award_count) - $award['record'][1];
            $award['limit'][2] = 120 - ($_award_count - $_award_expire - 1) * intval(120 / $_award_count) - $award['record'][2];
            $award['limit'][3] = 60 - ($_award_count - $_award_expire - 1) * intval(60 / $_award_count) - $award['record'][3];
            $award['limit'][4] = 20 - ($_award_count - $_award_expire - 1) * intval(20 / $_award_count) - $award['record'][4];
            //var_dump('limit',$award['limit']);

            $award_rand = [];
            $award_rand = array_merge($award_rand,array_pad([], $award['limit'][4], 4));
            $award_rand = array_merge($award_rand,array_pad([], $award['limit'][3], 3));
            $award_rand = array_merge($award_rand,array_pad([], $award['limit'][2], 2));
            $award_rand = array_merge($award_rand,array_pad([], $award['limit'][1], 1));
            //var_dump($award_rand);

            $award['id'] = 0;
            if (0 < sizeof($award_rand)) {
                shuffle($award_rand);
                shuffle($award_rand);
                $award['id'] = $award_rand[rand(0, sizeof($award_rand) - 1)];
            }
            //var_dump($award_rand);
            //die();

            if (0 < $award['id']) {
//                var_dump($draw);
                //todo 判断奖品数量是否溢出
                $sqlstr = 'select count(*) as _count from tb_lucky_draw_record where lucky_draw_id = %s AND award_id = %s';
                $sqlstr = sprintf($sqlstr, $draw['id'], $award['id']);
                $result = Db::query($sqlstr);
                $award_record['count'] = ($result && 0 < sizeof($result) && isset($result[0]['_count'])) ? $result[0]['_count'] : 0;

                if ($award_record['count'] < $award['limit'][$award['id']]) {
                    $sqlstr = 'INSERT INTO `tb_lucky_draw_record` (`employee_id`, `award_id`, `create_date`, `lucky_draw_id`, `is_receive`) VALUES (%s, %s, "%s", %s, 0)';
                    $add_record = Db::execute(sprintf($sqlstr, $emoplyee['id'], $award['id'], date('Y-m-d H:i:s', time()), $draw['id']));


                    if (4 == $award['id']) {
                        $data['msg'] = $data['prize_info'] = '恭喜抢到了10奋斗金';

                        $this->addCredits(10, $emoplyee['id'], $draw['id']);
                        $add_fdj = true;
                    }
                    if (3 == $award['id']) {
                        $data['msg'] = $data['prize_info'] = '恭喜抢到了5奋斗金';
                        $this->addCredits(5,$emoplyee['id'],$draw['id']);
                        $add_fdj = true;
                    }
                    if (2 == $award['id']) {
                        $data['msg'] = $data['prize_info'] = '恭喜抢到了2奋斗金';
                        $this->addCredits(2,$emoplyee['id'],$draw['id']);
                        $add_fdj = true;
                    }
                    if (1 == $award['id']) {
                        $data['msg'] = $data['prize_info'] = '恭喜抢到了1奋斗金';
                        $this->addCredits(1,$emoplyee['id'],$draw['id']);

                        $add_fdj = true;
                    }

                    if ($add_record && $add_fdj) {
                        $luckyDrawDao->commit();
                        return $data;
                    }
                }
            }
            $luckyDrawDao->rollback();
        }
        $data['msg'] = $this->noPoint();
        return $data;
    }


    private function noPoint()
    {
        $mesg = file_get_contents('yxj.json');
        $mesg = json_decode($mesg);
        $msg = $mesg[rand(0, sizeof($mesg) - 1)];
        return $msg;
    }

    public function fdj_sum($employee_id, $type = 4)
    {
        $sum = 0;
        $sqlstr = 'select id from tb_lucky_draw where type = %d';
        $sqlstr = sprintf($sqlstr, $type);
        $result = Db::query($sqlstr);

        if ($result && 0 < sizeof($result)) {
            foreach ($result as $key => $value) {
                $ids[] = $value['id'];
            }
        } else {
            return $sum;
        }

        $sqlstr = 'select * from tb_lucky_draw_record where employee_id = %d and lucky_draw_id in (%s)';
        $sqlstr = sprintf($sqlstr, $employee_id, join(',', $ids));
        $result = Db::query($sqlstr);

        if ($result && 0 < sizeof($result)) {
            foreach ($result as $key => $value) {
                if (4 == $value['award_id']) $sum = $sum + 10;
                if (3 == $value['award_id']) $sum = $sum + 5;
                if (2 == $value['award_id']) $sum = $sum + 2;
                if (1 == $value['award_id']) $sum = $sum + 1;
            }
        } else {
            return $sum;
        }
        return $sum;
    }


    //抽奖
    public function luckyDraw($luckydrawId, $user_id, $isticket)
    {
        $employeeDao = new EmployeeDao();
        $luckyDrawDao = new LuckyDrawDao();
        $employeeDao->startTrans();

        if ($isticket) {
            //年会活动整点时间判断
            $day = date('d');
            $h = date('H');
            if ($day != 13 || !in_array($h, [14, 15, 16, 17, 18])) {
                $this->error = '砸蛋时间点还未到';
                return false;
            }
            $luckyDrawRecordDao = new LuckyDrawRecordDao();
            if ($luckyDrawRecordDao->getByDayHour()) {
                $this->error = $h . "点已经砸过";
                return false;
            }

        }


        //测试积分或抽奖券

        $tickRecordid = $this->checkLukcyTicket($user_id, $luckydrawId);
//        $employee = $employeeDao->get_by_id($user_id);
        $luckyDraw = $luckyDrawDao->getById($luckydrawId);
        if (time() < strtotime($luckyDraw['start_date'])) {
            $this->error = '活动还未开始';
            return false;
        }
        if (!$tickRecordid) {
            if (!empty($isticket)) {
                //仅用抽奖券
                $this->error = '抽奖券已用完';
                return false;
            }
            if ($this->checkPoints($user_id) < $luckyDraw['credits']) {
                //积分不够
                $this->error = '奋斗金不足';
                return false;
            }
        }


        $award_arr = $this->getAwardByLuckyDrawId($luckydrawId);

        while (1) {
            if (!empty($isticket)) {
                $canwinning = $this->canWinning($user_id);
            } else {
                $canwinning = true;
            }
            if ($canwinning) {
                $rid = $this->draw($award_arr);
            } else {
                $rid = 0;
            }

            if ($rid === 0) {
                $mesg = file_get_contents('luckymesg.json');
                $mesg = json_decode($mesg);
                $award_arr[0]['msg'] = $mesg[rand(0, 99)];
            }

            $luckydrawAwardDao = new LuckyDrawAwardDao();
            if ($rid) {
                $award = $luckydrawAwardDao->getByAwardId($rid);
                if ($award['left_number'] < 1) {
                    //当前奖品已抽完从新抽一次
                    unset($award_arr[$rid]);
                    if (empty($award_arr)) {
                        //所有奖品都被抽完
                        $rid = false;
                        break;
                    }
                } else {
                    break;
                }
            } else {
                //默认谢谢参与
                break;
            }
        }

        if ($rid !== false) {
            $res['yes'] = $award_arr[$rid]; //中奖项

            if ($rid) {
                //减奖品剩余数量
                if ($luckydrawAwardDao->leftNumberMinusOne($rid, $award['left_number']) == false) {
                    $employeeDao->rollback();
                    $this->error = '系统繁忙';
                    return false;
                }
            }
            if (!$tickRecordid) {
                //扣积分
//                $effect1 = $employeeDao->where('id',$user_id)->setDec('points',$luckyDraw['credits']);

                //写积分流水纪录
                $creditsRecordDao = new CreditsRecodDao();
                $recordid = $creditsRecordDao->insertGetId(['credits' => 0 - $luckyDraw['credits'], 'create_date' => date('Y-m-d H:i:s'), 'tb_employee_id' => $user_id]);
                //写积分消耗表
                $creditsReduceDao = new CreditsReduceDao();
                $creditsReduceDao->save(['credits' => $luckyDraw['credits'], 'reduce_type' => $luckydrawId, 'record_id' => $recordid, 'employee_id' => $user_id]);
            } else {
                //核销抽奖券
                $luckyTicketRecordDao = new luckyTicketRecordDao();
                $luckyTicketRecordDao->where(['id' => $tickRecordid])->setField('status', 1);
            }


            //写抽奖纪录。
            $luckyDrawRecordDao = new LuckyDrawRecordDao();
            if ($rid === 0) {
                $luckyDrawRecordDao->save(['lucky_draw_id' => $luckyDraw['id'], 'employee_id' => $user_id, 'award_id' => $rid, 'create_date' => date('Y-m-d H:i:s'), 'lucky_number' => $award_arr[0]['msg']]);
            } else {
                $luckyDrawRecordDao->save(['lucky_draw_id' => $luckyDraw['id'], 'employee_id' => $user_id, 'award_id' => $rid, 'create_date' => date('Y-m-d H:i:s')]);
            }


        } else {
            $employeeDao->rollback();
            $this->error = '奖品已经抽完';
            return false;
        }

        $employeeDao->commit();
        return $res['yes'];

    }

    //积分奋斗值
    public function checkPoints($user_id)
    {
        $creditRecordDao = new CreditsRecodDao();
        $creditIncreasementDao = new CreditsIncreasementDao();
        $recordSum = $creditRecordDao->sumByUserid($user_id);
        $freezenSum = $creditIncreasementDao->sumFreezenByUserid($user_id);
        return bcsub($recordSum , $freezenSum,2);

    }

    public function checkLukcyTicket($userid, $drawid)
    {
        $luckyTicketDao = new LuckyTicketDao();
        $luckyTicketRecordDao = new luckyTicketRecordDao();
        //首先使用活动专用券
        $luckyTicket = $luckyTicketDao->getByActivity($drawid);
        if (!empty($luckyTicket)) {
            $luckyTicket = collection($luckyTicket)->toArray();
            $luckyTicketids = array_column($luckyTicket, 'id');
            $record = $luckyTicketRecordDao->getByEmployeeAndTicketids($userid, $luckyTicketids);
            if (!empty($record)) {
                return $record['id'];
            }
        }

        //通用券
        $luckyTicket = $luckyTicketDao->getByType();
        $luckyTicket = collection($luckyTicket)->toArray();
        if (!empty($luckyTicket)) {
            $luckyTicketids = array_column($luckyTicket, 'id');
            $record = $luckyTicketRecordDao->getByEmployeeAndTicketids($userid, $luckyTicketids);
            if (!empty($record)) {
                return $record['id'];
            }
        }

        //没有抽奖卷
        return false;

    }


    public function get_rand($proArr)
    {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }

    //抽取奖品
    public function draw($award_arr)
    {
        foreach ($award_arr as $key => $val) {
            $arr[$val['id']] = $val['v'];
        }
        $rid = $this->get_rand($arr); //根据概率获取奖项id
        return $rid;
    }

    //积分领奖
    public function getPrize($recordid, $userid, $awardid)
    {
        $luckyDrawRecord = new LuckyDrawRecordDao();
        $drawRecord = $luckyDrawRecord->getByRecordId($recordid);
        if (empty($drawRecord) || !empty($drawRecord['is_receive'])) {
            return false;
        }

        //积分奖励直接加积分
        $employeeDao = new EmployeeDao();
        $luckyDrawAward = new LuckyDrawAwardDao();
        $award = $luckyDrawAward->getByAwardId($awardid);
        $employeeDao->startTrans();
        $result1 = $employeeDao->where('id', $userid)->setInc('points', $award['credits']);

        //设置领取状态
        $drawRecord->is_receive = 1;
        $result = $drawRecord->save();

        //写积分纪录
        $creditsRecordDao = new CreditsRecodDao();
        $result2 = $creditsRecordDao->save(['remark' => '抽奖中积分', 'type' => 1, 'credits' => $award['credits'], 'create_date' => date('Y-m-d H:i:s'), 'tb_employee_id' => $userid]);
        if ($result1 !== false && $result2 !== false && $result !== false) {
            $employeeDao->commit();
            return true;
        }

        $employeeDao->rollback();
        return false;
    }

    public function luckyApply($userid, $drawid, $number)
    {

        //查询奖品
        $luckyDrawAwardDao = new LuckyDrawAwardDao();
        $luckyDrawAward = $luckyDrawAwardDao->getByLuckyDrawId($drawid);
        if (empty($luckyDrawAward)) {
            $this->error = '数据有误';
            return false;
        }
        $rid = 0 - $luckyDrawAward[0]['id'];

        $employeeDao = new EmployeeDao();
        $luckyDrawDao = new LuckyDrawDao();
        $employeeDao->startTrans();

        $employee = $employeeDao->get_by_id($userid);
        $luckyDraw = $luckyDrawDao->getById($drawid);

        if ($luckyDraw['type'] == -2) {
            $this->error = '活动已被终止';
            return false;
        }
        if (time() < strtotime($luckyDraw['start_date'])) {
            $this->error = '活动还未开始';
            return false;
        }
        if (time() > strtotime($luckyDraw['end_date'])) {
            $this->error = '活动已结束';
            return false;
        }
        if ($this->checkPoints($userid) < $luckyDraw['credits'] * $number) {
            //积分不够
            $this->error = '奋斗金不足';
            return false;
        }


        //测试是否已经报名
        $luckyDrawRecordDao = new LuckyDrawRecordDao();
//        if($luckyDrawRecordDao->getByUseridAndDrawid($userid,$drawid)){
//            $this->error = '已报名不可重复报名';
//            return false;
//        }

        //减剩余名额数量
        $luckyDrawDao = new LuckyDrawDao();
        $luckyDraw = $luckyDrawDao->getById($drawid);
        $oldLeft = $luckyDraw['left_with_people'];
        if ($oldLeft >= $number) {
            if ($luckyDrawDao->leftNumberMinusOne($drawid, $oldLeft, $number) == false) {
                $employeeDao->rollback();
                $this->error = '系统繁忙';
                return false;
            }
        } else {
            if($oldLeft > 0){
                $this->error = '份数不足还剩'.$oldLeft.'份可购';
            }else{
                $this->error = '已筹满，不可参与';
            }

            return false;
        }

////        //扣积分
////        $effect1 = $employeeDao->where('id',$userid)->setDec('points',$luckyDraw['credits']*$number);
//
//        //写积分纪录
//        $creditsRecordDao = new CreditsRecodDao();
//        $creditsRecordDao->save(['remark'=>$luckyDraw['title'],'type'=>2,'credits'=>$luckyDraw['credits']*$number,'create_date'=>date('Y-m-d H:i:s'),'tb_employee_id'=>$userid]);

        //写积分流水纪录
        $creditsRecordDao = new CreditsRecodDao();
        $recordid = $creditsRecordDao->insertGetId(['credits' => 0 - $luckyDraw['credits'] * $number, 'create_date' => date('Y-m-d H:i:s'), 'tb_employee_id' => $userid]);
        //写积分消耗表
        $creditsReduceDao = new CreditsReduceDao();
        $creditsReduceDao->save(['credits' => $luckyDraw['credits'] * $number, 'reduce_type' => $drawid, 'record_id' => $recordid, 'employee_id' => $userid]);

        //写抽奖纪录。
        $baseData = ['lucky_draw_id' => $luckyDraw['id'], 'employee_id' => $userid, 'award_id' => $rid, 'create_date' => date('Y-m-d H:i:s')];
        $saveData = [];
        $luckyNumber = [];
        for ($i = 0; $i < $number; $i++) {
            $index = uniqid('ld') . (string)rand(99999999, 1000000000);
            $luckyNumber[] = $index;
            $baseData['lucky_number'] = $index;
            $saveData[] = $baseData;
        }
        $luckyDrawRecordDao->insertAll($saveData);


//        if($oldLeft == 1){
//            //报名人数已满修改记录参数中奖者
//            $result = $this->getLucker($drawid);
//            if(!$result){
//                $this->error = '系统繁忙';
//                $employeeDao->rollback();
//                return false;
//            }
//        }

        $employeeDao->commit();
        return ['lucky_number' => $luckyNumber, 'points' => $employee['points'] - $luckyDraw['credits'] * $number];
    }


    public function getLucker($drawid)
    {
        $luckyDrawRecordDao = new LuckyDrawRecordDao();
        $records = $luckyDrawRecordDao->getByDrawId($drawid);
        if (empty($records)) {
            return false;
        }
        $total = count($records);
        $lucker = rand(0, $total - 1);
        foreach ($records as $key => $record) {
            if ($key == $lucker) {
                //中奖
                $record->award_id = abs($record['award_id']);
            } else {
                //未中奖
                $record->award_id = 0;
            }
            if ($record->save() === false) {
                return false;
            }
        }
        $luckyDrawDao = new LuckyDrawDao();
        $luckyInfo = $luckyDrawDao->getById($drawid);
        $luckyInfo['status'] = 1;
        $luckyInfo->save();
        return true;
    }

    public function getluckyApplyInfo($userid, $drawid)
    {

        //活动信息
        $luckyDrawDao = new LuckyDrawDao();
        $draw = $luckyDrawDao->getById($drawid);
        if (empty($draw)) {
            $this->error = '活动已取消';
            return false;
        }

        //活动奖品信息
        $luckyDrawAwardDao = new LuckyDrawAwardDao();
        $award = $luckyDrawAwardDao->getByLuckyDrawId($drawid);
        if (empty($award)) {
            $this->error = '数据有误';
            return false;
        }

        //报名记录
        $luckyDrawRecordDao = new LuckyDrawRecordDao();
        $record = $luckyDrawRecordDao->getByUseridAndDrawid($userid, $drawid);
        $ticketUsedNumber = $luckyDrawRecordDao->countTicke($userid,$drawid);
        $data = [];
        if($draw['end_date']> date('Y-m-d H:i:s')){
            $data['ticketnumber'] = $draw['frequency'] - $ticketUsedNumber;
        }else{
            $data['ticketnumber'] = 0;
        }
        if (empty($record)) {
            $data['is_apply'] = 0;
            $data['record'] = [];
        } else {
            if ($record[0]['award_id'] > 0) {
                //中奖
                $data['is_apply'] = 1;
            } elseif ($record[0]['award_id'] == 0) {
                //未中奖
                $data['is_apply'] = 2;
            } elseif ($record[0]['award_id'] < 0) {
                $data['is_apply'] = 3;
            }

        }

        //活动信息
        if ($data['is_apply'] == 3) {
            $draw['timestamp'] = strtotime($draw['end_date']);
            $draw['minute'] = floor((($draw['timestamp'] - time()) % 3600) / 60);
            $draw['hour'] = floor((($draw['timestamp'] - time()) / 3600)) % 24;
            $draw['day'] = floor(floor(($draw['timestamp'] - time()) / 3600) / 24);
            $draw['minute'] = $draw['minute']>0?$draw['minute']:0;
            $draw['hour'] = $draw['hour']>0?$draw['hour']:0;
            $draw['day'] = $draw['day']>0?$draw['day']:0;
        }
        $draw['end_time'] = strtotime($draw['end_date']);
        $draw['start_time'] = strtotime($draw['start_date']);
        $draw['start_date1'] = date('Y-m-d',$draw['start_time']);
        $draw['end_date1'] = date('Y-m-d',$draw['end_time']);
        if($draw['status'] ==2){
            $credits = floor(floor(100 * ($draw['with_people'] - $draw['left_with_people'])/$draw['with_people'])*$draw['credits']*$draw['with_people']/1.4/100);
            $draw['originalcost'] = floor($draw['with_people']*$draw['credits']/1.4);
            $draw['przecredit'] = $credits;
        }
        $data['draw'] = $draw;
        $data['p20'] = floor($draw['with_people']*$draw['credits']/1.4*0.2);
        $data['p40'] = floor($draw['with_people']*$draw['credits']/1.4*0.4);
        $data['p60'] = floor($draw['with_people']*$draw['credits']/1.4*0.6);
        $data['p80'] = floor($draw['with_people']*$draw['credits']/1.4*0.8);

        //所需人数
        $data['with_people'] = $draw['with_people'];

        //参加人数
//        if(strtotime($draw['start_date'])<=time()){
//            //已经开始就显示一半进度条
//            $data['apply_people'] = ($draw['with_people'] - $draw['left_with_people'])/2 + $draw['with_people']/2;
//        }else{
//            $data['apply_people'] = 0;
//        }
        $data['apply_people'] = $draw['with_people'] - $draw['left_with_people'];
        $draw['with_people'] = floor($draw['with_people']/1.4);
        //花了多少积分
        if ($userid && $data['is_apply']) {
            $times = $luckyDrawRecordDao->where(['employee_id' => $userid, 'lucky_draw_id' => $drawid,'useticket'=>0])->count();
            $data['costPoints'] = $times * $draw['credits'];
            $numbers = $luckyDrawRecordDao->where(['employee_id' => $userid, 'lucky_draw_id' => $drawid])->count();
            $data['luckynumbers'] = $numbers;
        } else {
            $data['costPoints'] = 0;
        }

        //中奖者
        $employeeDao = new EmployeeDao();
        if ($data['is_apply'] == 1 || $data['is_apply'] == 2) {
            $record = $luckyDrawRecordDao->where(['lucky_draw_id' => $drawid, 'award_id' => ['gt', 0]])->find();
            $employee = $employeeDao->get_by_id($record['employee_id']);
            $site = empty($employee['site'])?$employee['d5']:$employee['site'];
            $data['lucker'] = ['lucky_number' => $record['lucky_number'], 'lucky_name' => $employee['name'],'site'=>$site];
        } else {
            $data['lucker'] = [];
        }
        //剩余积分数

        $luckyDraw = new LuckyDraw();
        $points = $luckyDraw->checkPoints($userid);
        $data['points'] = $points;
        $data['award'] = $award;
        return $data;

    }

    //大转盘最新中奖纪录
    public function getPrizeRecord($drawid)
    {
        $luckDrawRecordDao = new LuckyDrawRecordDao();
        $prizeRecord = $luckDrawRecordDao->getPrizeRecord($drawid);
        if (empty($prizeRecord)) {
            return [];
        }
        $luckyDrawRecordModel = new LuckyDrawRecord();
        array_walk($prizeRecord, [$luckyDrawRecordModel, 'addtionAwardName']);
        return $prizeRecord;

    }


    //夺宝随机开奖
    public function luckyer($drawid)
    {

        if ($this->checkLucker($drawid)) {
            return $this->getLucker($drawid);
        } else {
            return false;
        }

    }

    public function checkLucker($drawid)
    {
        $luckyDrawDao = new LuckyDrawDao();
        $luckyDraw = $luckyDrawDao->getById($drawid);
        $leftPeople = $luckyDraw['left_with_people'];

        if ($luckyDraw['type'] == -2) {
            $this->error = '活动已被终止，不可开奖';
            return false;
        }
        if ($leftPeople > 0) {
            $this->error = '报名未满';
            return false;
        }
        if (!(strtotime($luckyDraw['end_date']) < time())) {
            $this->error = '开奖时间未到';
            return false;
        }

        //判断是否已经开奖
        $luckyDrawRecordDao = new LuckyDrawRecordDao();
        $prize = $luckyDrawRecordDao->getPrizeRecord($drawid);
        if (!empty($prize)) {
            $this->error = '已经开奖，不可重复开奖';
            return false;
        }

        return true;
    }

    //夺宝活动设置用户中奖
    public function setLuckyer($drawid, $recordid)
    {

        if ($this->checkLucker($drawid)) {
            $luckyDrawRecordDao = new LuckyDrawRecordDao();
            $records = $luckyDrawRecordDao->getByDrawId($drawid);
            if (empty($records)) {
                return false;
            }

            $luckyDrawAwardDao = new LuckyDrawAwardDao();
            $award = $luckyDrawAwardDao->getByLuckyDrawId($drawid);
            if (empty($award)) {
                return false;
            }

            foreach ($records as $key => $record) {
                if ($record['id'] == $recordid) {
                    //中奖
                    $record->award_id = $award[0]['id'];
                } else {
                    //未中奖
                    $record->award_id = 0;
                }
                if ($record->save() === false) {
                    return false;
                }
            }
            $luckyDrawDao = new LuckyDrawDao();
            $luckyInfo = $luckyDrawDao->getById($drawid);
            $luckyInfo['status'] = 1;
            $luckyInfo->save();

            return true;
        }

        return false;

    }

    //夺宝活动历史中奖纪录
    public function luckyHistory($page, $pageSize)
    {
        $luckDrawDao = new LuckyDrawDao();
        $drawids = $luckDrawDao->getluckyids($page, $pageSize);
        if (empty($drawids)) {
            return [];
        }

        $luckDrawRecordDao = new LuckyDrawRecordDao();
        $record = $luckDrawRecordDao->getByDrawids($drawids);
        foreach ($record as &$value){
            $draw = $luckDrawDao->where(['id'=>$value['lucky_draw_id']])->find();
            $value['create_date'] = $draw['end_date'];
            $value['status'] = $draw['status'];
            if($value['status'] == 2){

                $credits = floor(floor(100 * ($draw['with_people'] - $draw['left_with_people'])/$draw['with_people'])*$draw['credits']*$draw['with_people']/1.4/100);
                $value['przecredit'] = $credits;
            }
        }
        $luckyDrawRecordModel = new LuckyDrawRecord();
        array_walk($record, [$luckyDrawRecordModel, 'addtionAwardName']);
        return $record;
    }

    public function luckDrawPrizeRecord($drawid, $page, $pagesize, $isrecord = 0)
    {
        $luckDrawRecordDao = new LuckyDrawRecordDao();
        $record = $luckDrawRecordDao->getByDrawidCondition($drawid, $page, $pagesize, $isrecord);
        $luckyDrawRecordModel = new LuckyDrawRecord();
        array_walk($record, [$luckyDrawRecordModel, 'addtionAwardName']);
        return $record;
    }

    public function getLastLuckyApplayInfo($limit)
    {
        $luckyDrawDao = new LuckyDrawDao();
        $luckyDraw = $luckyDrawDao->getNewstTwo($limit);
        if (empty($luckyDraw)) {
            return false;
        }

        $data = array();
        foreach ($luckyDraw as $draw) {
            $ifno = $this->getluckyApplyInfo(0, $draw['id']);
            if (!empty($ifno)) {
                $data[] = $ifno;
            }
        }

        return $data;
    }

    //用户夺宝参与纪录
    public function luckyApplyRecord($userid, $page, $pagesize)
    {
        $luckyDrawDao = new LuckyDrawDao();
        $luckyDraw = $luckyDrawDao->alias('l')->join(['tb_lucky_draw_record' => 'r'], 'l.id = r.lucky_draw_id')->group('r.lucky_draw_id')->where(['type' => ['in',[2,-2]], 'employee_id' => $userid])->order('start_date', 'desc')->limit($page * $pagesize, $pagesize)->column('l.id');
//        var_dump($luckyDrawDao->getLastSql());die;
        if (empty($luckyDraw)) {
            return [];
        }
        $luckyDraw = array_unique($luckyDraw);
        $data = array();
        foreach ($luckyDraw as $drawid) {
            $data[] = $this->getluckyApplyInfo($userid, $drawid);
        }

        return $data;
    }

    //我的幸运号
    public function getLuckyNumber($drawid, $userid)
    {
        $luckyDrawRecordDao = new LuckyDrawRecordDao();
        $record = $luckyDrawRecordDao->getLuckyNumber($drawid, $userid);
        $data = [];
        foreach ($record as $value) {
            $data[$value['create_date']][] = $value;
        }
        unset($value);
        $drawDao = new LuckyDrawDao();
        $draw = $drawDao->find($drawid);
        $result = [];
        foreach ($data as $key => $value) {
            $total = 0;
            foreach ($value as $key=>$record){
                if($record['useticket'] == 0){
                    $total++;
                }
            }
            $totalPoint = $total * $draw['credits'];
            $result[] = ['cost_points' => $totalPoint, 'date' => count($value), 'luckenumber' => $value];
        }

        return $result;
    }

    public function innerDrawid($userid)
    {
        $employeeInnerDao = new EmployeeInnerDao();
        $employeeActivityDao = new EmployeeActivityDao();

        $employeeInner = $employeeInnerDao->getByEmployeeid($userid);
        if (empty($employeeInner)) {
            //非场内员工
            $employeeActivity = $employeeActivityDao->where(['type' => 2, 'type' => 1])->find();
            if (!empty($employeeActivity)) {
                $drawids = ['draw_id' => $employeeActivity['id']];
            } else {
                //活动暂停
                return false;
            }

        } else {
            //场内员工优先选取活动
            $drawids = $employeeActivityDao->where(['status' => 1])->column('draw_id');
        }
//        $newyeaydrawDao = new NewYearDrawTimeDao();
//        //进行中的
//        $now = time();
//        $drawtime = $newyeaydrawDao->where(['starttime'=>['<=',$now],'end_time'=>['>',$now],'draw_id'=>['in',$drawids]])->order('end_time')->select();
//        if(!empty($drawtime)){
//            return $drawtime[0];
//        }
//
//        //活动时间之外的
//        $drawtime = $newyeaydrawDao->where(['starttime'=>['>',$now],'draw_id'=>['in',$drawids]])->select();
//        if(!empty($drawtime)){
//            return $drawtime[0];
//        }
        //活动结束
        return false;
    }

    public function canWinning($userid)
    {
        $employeeDao = new EmployeeDao();
        $employee = $employeeDao->find($userid);
        $mobile = $employee['contact_moblie'];
        $employeeinnerWinningDao = new EmployeeInnerWinningDao();
        $in = $employeeinnerWinningDao->getBymobile($mobile);
        if (!empty($in)) {
            return true;
        }

        $employeeoutWinningDao = new EmployeeOuterWinningDao();
        $out = $employeeoutWinningDao->getByMobile($mobile);
        if (empty($out)) {
            return false;
        }

        return true;
    }

    public function newyearluckyinfo($userid)
    {
        $drawtime = $this->innerDrawid($userid);
        if (empty($drawtime)) {
            return ['type' => 0, 'time' => []];
        }

        $now = time();
        if ($drawtime['starttime'] > $now) {
            //开始倒计时
            $diff = abs($now - $drawtime['starttime']);
            $day = floor($diff / (3600 * 24));
            $hour = floor(($diff / 3600)) % 24;
            $minute = floor(($diff % 3600) / 60);
            $second = $diff % 60;
            return ['type' => 1, 'time' => ['day' => $day, 'hour' => $hour, 'minute' => $minute, 'second' => $second]];
        }

        if ($drawtime['end_time'] > $now && $drawtime['starttime'] < $now) {
            //结束倒计时
            $diff = abs($now - $drawtime['end_time']);
            $day = floor($diff / (3600 * 24));
            $hour = floor(($diff / 3600)) % 24;
            $minute = floor(($diff % 3600) / 60);
            $second = $diff % 60;
            return ['type' => 2, 'time' => ['day' => $day, 'hour' => $hour, 'minute' => $minute, 'second' => $second]];
        }

        return ['type' => 0, 'time' => []];
    }


    public function getReady($user_id, $type = 1)
    {

        $lukyDrawDao = new LuckyDrawDao();
        $result = $lukyDrawDao->getActive($type);
        if (0 < sizeof($result)) {
            //活动是否已经开始
            return $result[0];
        }

        $result = $lukyDrawDao->getWait($type);
        if (0 < sizeof($result)) {
            //活动最早那个开始
            return $result[0];
        }

        return false;

    }

    public function endLucky($drawid)
    {
        $luckyDrawDao = new LuckyDrawDao();
        $luckyInfo = $luckyDrawDao->getById($drawid);
        //还未到结束时间不能结束
        if (strtotime($luckyInfo['end_date']) > time()) {
            $this->error = '活动截止时间还未到';
            return false;
        }
        //已经开奖不可退款
        $luckyDrawRecordDao = new LuckyDrawRecordDao();
        $record = $luckyDrawRecordDao->getByDrawId($drawid);
        if (!empty($record) && $record[0]['award_id'] >= 0) {
            $this->error = '已经开奖不能退款';
            return false;
        }
        //筹集份数超过百分之二不可关闭
        if(!empty($record) && (count($record)/($luckyInfo['with_people'])>0.2)){
            $this->error = '已经筹集超过百分之二十不可退款';
            return false;
        }

        //已退款不可重复退款
        if ($luckyInfo['type'] == -2) {
            $this->error = '已经退款，不可重复退款';
            return false;
        }
        //结束并退款
        $creditsRecordDao = new CreditsRecordDao();
        $creditsIncreasementDao = new CreditsIncreasementDao();
        $creditsRecordDao->startTrans();
        //结束
        $luckyInfo['type'] = -2;
        $luckyInfo['status'] = 3;
        try {
            $luckyInfo->save();
        } catch (PDOException $exception) {
            $creditsRecordDao->rollback();
            $this->error = $exception->getMessage();
            return false;
        }

        $employeecount = $luckyDrawRecordDao->groupByEmployeeid($drawid);
        foreach ($employeecount as $value) {
            $record = ['credits' => $luckyInfo['credits'] * $value['total'], 'tb_employee_id' => $value['employee_id'], 'create_date' => date('Y-m-d H:i:s')];
            try {
                $recordid = $creditsRecordDao->insertGetId($record);
            } catch (PDOException $exception) {
                $creditsRecordDao->rollback();
                $this->error = $exception->getMessage();
                return false;
            }

            $increasement = ['record_id' => $recordid, 'credits' => $value['total'] * $luckyInfo['credits'], 'employee_id' => $value['employee_id'],
                'unfreeze_time' => date('Y-m-d'), 'isconfirm' => 2, 'grand_type' => $drawid, 'type' => 2, 'remark' => $luckyInfo['title'] . ' 开奖失败退款'];
            try {
                $creditsIncreasementDao->insert($increasement);
            } catch (PDOException $exception) {
                $creditsRecordDao->rollback();
                $this->error = $exception->getMessage();
            }

            unset($increasement);
            unset($record);
        }
        $creditsRecordDao->commit();
        return true;
    }

    //添加奋斗金
    protected function addCredits($credirs, $employeid, $drawid,$remark = '元宵抽奖奖励')
    {

        $creditsRecordDao = new CreditsRecordDao();
        $creditsIncreasementDao = new CreditsIncreasementDao();

        $record = ['credits' => $credirs, 'tb_employee_id' => $employeid, 'create_date' => date('Y-m-d H:i:s')];
        $recordid = $creditsRecordDao->insertGetId($record);
        $increasement = ['record_id' => $recordid, 'credits' => $credirs, 'employee_id' => $employeid,
            'unfreeze_time' => date('Y-m-d'), 'isconfirm' => 2, 'grand_type' => $drawid, 'type' => 2, 'remark' => $remark . $credirs . '奋斗金'];
        $creditsIncreasementDao->insert($increasement);

    }

    //有夺宝券的夺宝报名
    public function newLuckyApply($userid, $drawid, $number,$ticketnumber)
    {

        //查询奖品
        $luckyDrawAwardDao = new LuckyDrawAwardDao();
        $luckyDrawAward = $luckyDrawAwardDao->getByLuckyDrawId($drawid);
        if (empty($luckyDrawAward)) {
            $this->error = '数据有误';
            return false;
        }
        $rid = 0 - $luckyDrawAward[0]['id'];

        $employeeDao = new EmployeeDao();
        $luckyDrawDao = new LuckyDrawDao();
        $employeeDao->startTrans();

        $employee = $employeeDao->get_by_id($userid);
        $luckyDraw = $luckyDrawDao->getById($drawid);

        if ($luckyDraw['type'] == -2) {
            $this->error = '活动已被终止';
            return false;
        }
        if (time() < strtotime($luckyDraw['start_date'])) {
            $this->error = '活动还未开始';
            return false;
        }
        if (time() > strtotime($luckyDraw['end_date'])) {
            $this->error = '活动已结束';
            return false;
        }

        //测试夺宝券
        $luckyDrawRecordDao = new LuckyDrawRecordDao();
        $ticketUsedNumber = $luckyDrawRecordDao->countTicke($userid,$drawid);
        if($luckyDraw['frequency']- $ticketUsedNumber < $ticketnumber){
            //夺宝券不够
            $this->error = '夺宝券不够';
            return false;
        }
        if ($this->checkPoints($userid) < $luckyDraw['credits'] * ($number-$ticketnumber)) {
            //积分不够
            $this->error = '奋斗金不足';
            return false;
        }


        //测试是否已经报名

//        if($luckyDrawRecordDao->getByUseridAndDrawid($userid,$drawid)){
//            $this->error = '已报名不可重复报名';
//            return false;
//        }

        //减剩余名额数量
        $luckyDrawDao = new LuckyDrawDao();
        $luckyDraw = $luckyDrawDao->getById($drawid);
        $oldLeft = $luckyDraw['left_with_people'];
        if ($oldLeft >= $number) {
            if ($luckyDrawDao->leftNumberMinusOne($drawid, $oldLeft, $number) == false) {
                $employeeDao->rollback();
                $this->error = '系统繁忙';
                return false;
            }
        } else {
            if($oldLeft > 0){
                if($oldLeft <= 10){
                    $this->error = '份数不足还剩'.$oldLeft.'份可购';
                }else{
                    $this->error = '份数不足';
                }

            }else{
                $this->error = '已筹满，不可参与';
            }
            return false;
        }

////        //扣积分
////        $effect1 = $employeeDao->where('id',$userid)->setDec('points',$luckyDraw['credits']*$number);
//
//        //写积分纪录
//        $creditsRecordDao = new CreditsRecodDao();
//        $creditsRecordDao->save(['remark'=>$luckyDraw['title'],'type'=>2,'credits'=>$luckyDraw['credits']*$number,'create_date'=>date('Y-m-d H:i:s'),'tb_employee_id'=>$userid]);

        //写积分流水纪录
        if($number > $ticketnumber){
            $creditsRecordDao = new CreditsRecodDao();
            $recordid = $creditsRecordDao->insertGetId(['credits' => 0 - $luckyDraw['credits'] * ($number-$ticketnumber), 'create_date' => date('Y-m-d H:i:s'), 'tb_employee_id' => $userid]);
            //写积分消耗表
            $creditsReduceDao = new CreditsReduceDao();
            $creditsReduceDao->save(['credits' => $luckyDraw['credits'] * ($number-$ticketnumber), 'reduce_type' => $drawid, 'record_id' => $recordid, 'employee_id' => $userid]);
        }


        //写抽奖纪录。
        $baseData = ['lucky_draw_id' => $luckyDraw['id'], 'employee_id' => $userid, 'award_id' => $rid, 'create_date' => date('Y-m-d H:i:s')];
        $saveData = [];
        $luckyNumber = [];
        for ($i = 0; $i < $number-$ticketnumber; $i++) {
            $index = uniqid('ld') . (string)rand(99999999, 1000000000);
            $luckyNumber[] = $index;
            $baseData['lucky_number'] = $index;
            $baseData['useticket'] = 0;
            $saveData[] = $baseData;
        }
        for ($i = 0; $i < $ticketnumber; $i++) {
            $index = uniqid('ld') . (string)rand(99999999, 1000000000);
            $luckyNumber[] = $index;
            $baseData['lucky_number'] = $index;
            $baseData['useticket'] = 1;
            $saveData[] = $baseData;
        }
        $luckyDrawRecordDao->insertAll($saveData);


//        if($oldLeft == 1){
//            //报名人数已满修改记录参数中奖者
//            $result = $this->getLucker($drawid);
//            if(!$result){
//                $this->error = '系统繁忙';
//                $employeeDao->rollback();
//                return false;
//            }
//        }

        $employeeDao->commit();
        return ['lucky_number' => $luckyNumber, 'points' => $employee['points'] - $luckyDraw['credits'] * $number];
    }

    public function luckyParam($drawid,$userid,$number,$ticketnumber){
        $luckyDarwDao = new LuckyDrawDao();
        $number = $number-$ticketnumber;
        if($number<0){
            $number = 0;
        }
        $draw = $luckyDarwDao->getById($drawid);
//        if($draw['left_with_people'] < $number){
//            $this->error = '份数不足';
//            return false;
//        }
        $cost = $draw['credits']*$number;
        $point = $this->checkPoints($userid);
        $left = $point - $cost;
//        if($left < 0){
//            $this->error = '奋斗金不足';
//            return false;
//        }

        return ['cost'=>$cost,'left'=>$left,'number'=>$number];
    }

    public function getCreditsLucker($drawid){
        $luckyDrawRecordDao = new LuckyDrawRecordDao();
        $records = $luckyDrawRecordDao->getByDrawId($drawid);
        $luckyDrawDao = new LuckyDrawDao();
        $luckyDraw = $luckyDrawDao->getById($drawid);

        if ($luckyDraw['type'] == -2) {
            $this->error = '活动已被终止，不可开奖';
            return false;
        }

        if (!(strtotime($luckyDraw['end_date']) < time())) {
            $this->error = '开奖时间未到';
            return false;
        }

        //判断是否已经开奖
        $prize = $luckyDrawRecordDao->getPrizeRecord($drawid);
        if (!empty($prize)) {
            $this->error = '已经开奖，不可重复开奖';
            return false;
        }



        $luckyInfo = $luckyDrawDao->getById($drawid);
        if(count($records)/$luckyInfo['with_people'] < 0.2){
            $this->error='不够百分之二十不可积分开奖';
            return false;
        }
        if($luckyInfo['left_with_people'] == 0){
            $this->error='已筹满不可积分开奖';
            return false;
        }
        if (empty($records)) {
            return false;
        }
        $total = count($records);
        $lucker = rand(0, $total - 1);
        foreach ($records as $key => $record) {
            if ($key == $lucker) {
                //中奖
                $record->award_id = abs($record['award_id']);
                //添加奖励积分
                #$credits = count($records)*floor($luckyInfo['credits']/1.4);
                $credits = floor(floor(100 * ($luckyInfo['with_people'] - $luckyInfo['left_with_people'])/$luckyInfo['with_people'])*$luckyInfo['credits']*$luckyInfo['with_people']/1.4/100);
                $this->addCredits($credits,$record['employee_id'],$drawid,'夺宝奖励：'.$luckyInfo['title']);
            } else {
                //未中奖
                $record->award_id = 0;
            }
            if ($record->save() === false) {
                return false;
            }
        }
        $luckyInfo['status'] = 2;
        $luckyInfo->save();
        return true;
    }
}
