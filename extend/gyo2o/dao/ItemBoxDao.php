<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7 0007
 * Time: 下午 5:57
 */

namespace gyo2o\dao;

use think\Model;

class ItemBoxDao extends Model
{
    // 表名
    protected $table = 'tb_item_box';
    /**
     * 根据套餐id获取套餐详情数据
     * @param $boxId
     *
     * @return mixed
     */
    public function get_health_box($boxId){
        return $this->where(array('box_id'=>$boxId))->select();
    }

    /**
     * 获取私人定制套餐
     * @return mixed
     */
    public function get_private_box(){
        return $this->where('dietitian_id is not null')->select();
    }

}
