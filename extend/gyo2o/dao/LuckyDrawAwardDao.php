<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27 0027
 * Time: 下午 1:43
 */

namespace gyo2o\dao;


use think\Model;

class LuckyDrawAwardDao extends Model
{
    protected $table = 'tb_lucky_draw_award';
    protected $append = ['type_text'];
    private $award_type = ['1'=>'实物','2'=>'积分'];

    public function getAwardType(){
        return $this->award_type;
    }

    public function getTypetextAttr($value,$data){
        return $this->award_type[$data['type']];
    }

    public function getImgidAttr($value,$data){
        if(empty($value)){
            return '';
        }
        $attachment = new attachmentDao();
        return $attachment->getUrlAttr($value);
    }

    public function setImgidAttr($value,$data){
        if(empty($value)){
            return 0;
        }
        if(is_numeric($value)){
            return $value;
        }else{
            $attachment = new attachmentDao();
            $id = $attachment->getImgid($value);
            return $id?$id:0;
        }

    }

    //根据活动ID获取活动奖品
    public function getByLuckyDrawId($luckyDrawId){
        if(empty($luckyDrawId)){
            return false;
        }
        return $this->where('lucky_draw_id',$luckyDrawId)->select();
    }

    //根据ID获取奖品信息

    public function getByAwardId($awardid){
        if(empty($awardid)){
            return false;
        }
        return $this->where('id',$awardid)->find();
    }

    //奖品剩余数量减1
    public function leftNumberMinusOne($awardid,$oldLeftNumber){
        return $this->where(['id'=>$awardid,'left_number'=>$oldLeftNumber])->setDec('left_number',1);
    }
}