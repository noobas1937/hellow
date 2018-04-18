<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/5 0005
 * Time: 下午 2:52
 */

namespace app\admin\controller\lucky;


use app\common\controller\Backend;
use gyo2o\dao\LuckyDrawDao;

class Luckyer extends Backend
{

    /**
     * 积分夺宝开奖
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\\gyo2o\\dao\\LuckyDrawRecordDao');

    }

    public function index($ids=null)
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);

        if ($this->request->isAjax())
        {
            $luckyDrawRecordModel = new \gyo2o\model\LuckyDrawRecord();
            list($where, $sort, $order, $offset, $limit) = $this->buildparams('employee_id');
            $result = $luckyDrawRecordModel->getLuckyDrawRecord($where, $sort, $order, $offset, $limit,$ids);
            return json($result);
        }
        $luckDrawDao = new LuckyDrawDao();
        $lucky = $luckDrawDao->getById($ids);
        $this->assign('type',$lucky['type']);
        $this->assign('ids',$ids);
        return $this->view->fetch();
    }

    public function luckyer(){
        $drawid = $this->request->post('draw_id');
        $luckyDrawModel = new \gyo2o\model\LuckyDraw();
        $result = $luckyDrawModel->luckyer($drawid);
        if($result){
            return json(['msg'=>'随机开奖成功!']);
        }else{
            return json(['msg'=>$luckyDrawModel->getError()]);
        }

    }

    public function edit($ids = '')
    {
        $enterprise = new \gyo2o\model\LuckyDrawRecord();
        if($this->request->isPost()){
            $params = $this->request->post("row/a", [], 'strip_tags');
            $result = $enterprise->edit($ids,$params);
            $this->result_return($result);
        }
        $row = $enterprise->get_one($ids);
        if(!$row){
            $this->error('Parameter can not be empty');
        }

        $this->view->assign('row',$row);
        return $this->view->fetch();
    }

    public function setLuckyer(){
        $drawid = $this->request->post('draw_id');
        $recordid = $this->request->post('record_id');
        $luckyDrawModel = new \gyo2o\model\LuckyDraw();
        $result = $luckyDrawModel->setLuckyer($drawid,$recordid);
        if($result){
            return json(['msg'=>'设置成功!']);
        }else{
            return json(['msg'=>$luckyDrawModel->getError()]);
        }

    }

    public function end(){
        $drawid = $this->request->post('draw_id');
        $luckyDrawModel = new \gyo2o\model\LuckyDraw();
        $result = $luckyDrawModel->endLucky($drawid);
        if($result){
            return json(['msg'=>'结束夺宝活动且退还奋斗金成功']);
        }else{
            return json(['msg'=>$luckyDrawModel->getError()]);
        }
    }

    public function creditsLuckyer(){
        $drawid = $this->request->post('draw_id');
        $luckyDrawModel = new \gyo2o\model\LuckyDraw();
        $result = $luckyDrawModel->getCreditsLucker($drawid);
        if($result){
            return json(['msg'=>'奋斗金开奖成功!']);
        }else{
            return json(['msg'=>$luckyDrawModel->getError()]);
        }

    }

}