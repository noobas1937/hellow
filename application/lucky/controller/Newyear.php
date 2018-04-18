<?php
/**
 * Created by PhpStorm.
 * User: ggjrw
 * Date: 18/2/13
 * Time: 下午2:33
 */

namespace app\lucky\controller;

use app\common\controller\Api;
use gyo2o\dao\EmployeeDao;
use gyo2o\dao\LuckyDrawAwardDao;
use gyo2o\model\LuckyDraw;
use gyo2o\model\LuckyDrawRecord;
use gyo2o\model\LuckyTicketRecordModel;
use fast\Http;

class Newyear extends Api
{
    protected $draw_type = 4;

    protected function timediff($timediff)
    {
        if ($timediff > 0) {
            $date['day'] = floor($timediff / (3600 * 24));
            $timediff -= $date['day'] * 3600 * 24;

            $date['hour'] = floor($timediff / 3600);
            $timediff -= $date['hour'] * 3600;

            $date['minute'] = floor($timediff / 60);

            $date['second'] = $timediff - $date['minute'] * 60;
        }
        return $date;
    }

    //获取5次机会
    protected function getLuckyTicketExt()
    {
        $json = ["status" => "success", "code" => 3, "msg" => "", 'data' => ['total' => 1]];
        //return json($json);
        $userid = $this->request->post('user_id');

        //员工身份判断
        $userid = $this->request->post('user_id');
        $employeeModel = new EmployeeDao();
        $emoplyee = $employeeModel->get_by_id($userid);
        $emoplyee = $emoplyee->toArray();
        if (0 == sizeof($emoplyee)) {
            return json(["status" => "failer", "code" => 4, "msg" => "员工不存在"]);
        }

        $json['data']['total'] = 1;

        //获取当前活动数据
        $luckydrayModel = new LuckyDraw();
        $ready = $luckydrayModel->getReady($userid, $this->draw_type);
        if (false == $ready) {
            return json($json);
        }

        $start_date = strtotime($ready['start_date']) - 1000000000;
        $end_date = strtotime($ready['end_date']) - 1000000000;
        $current_time = time() - 1000000000;

        if ($current_time < $start_date) {
            return json($json);
        }

        if ($emoplyee && $ready) {
            $luckyDrawRecord = new LuckyDrawRecord();
            $result = $luckyDrawRecord->getUserRecoud($emoplyee['id'], $ready['id']);
            if (0 < sizeof($result)) $json['data']['total'] = 0;;
        } else {
            return json($json);
        }
        return json($json);
    }

    //获取奖品
    protected function luckyDrawExt()
    {
        //员工身份判断
        $userid = $this->request->post('user_id');
        $employeeModel = new EmployeeDao();
        $emoplyee = $employeeModel->get_by_id($userid);

        if ($emoplyee && is_object($emoplyee) && 0 < $emoplyee->getAttr('id')) {
            //获取当前活动数据
            $luckydrayModel = new LuckyDraw();
            $ready = $luckydrayModel->getReady($userid, $this->draw_type);
            if (false == $ready) {
                return json(["status" => "failer", "code" => 4, "msg" => "暂无活动"]);
            }
            $result = $luckydrayModel->luckyDrawExt($ready, $emoplyee);
            return json(["status" => "success", "code" => 3, "msg" => "", 'data' => $result]);
        } else {
            return json(["status" => "failer", "code" => 4, "msg" => "员工不存在"]);
        }
    }

    //获取最新活动
    protected function newyearDrawInfoExt()
    {
        $userid = $this->request->post('user_id');
        $luckydrayModel = new LuckyDraw();
        //$result = $luckydrayModel->newyearluckyinfo($userid);
        $ready = $luckydrayModel->getReady($userid, $this->draw_type);

        if (false == $ready) {
            $result = ['type' => 0, 'time' => ['hour' => 24, 'minute' => 0, 'second' => 0]];
        } else {
            $result['id'] = $ready['id'];
            $start_date = strtotime($ready['start_date']) - 1000000000;
            $end_date = strtotime($ready['end_date']) - 1000000000;
            $current_time = time() - 1000000000;

            if ($start_date > $current_time) {
//                echo "开始倒计时";
                $result['type'] = 1;
                $timediff = $start_date - $current_time;
            } else {
//                echo "结束倒计时";
                $result['type'] = 2;
                $timediff = $end_date - $current_time;
            }
            $result['time'] = $this->timediff($timediff);
        }
        return json(['status' => 'success', 'code' => 3, 'data' => $result]);
    }

    public function fdjCount()
    {

        $userid = $this->request->post('user_id');
        $luckydrayModel = new LuckyDraw();
        $result = $luckydrayModel->fdj_sum($userid);
        return json(["status" => "success", "code" => 3, "msg" => "", 'data' => ['count' => $result]]);
    }
}
