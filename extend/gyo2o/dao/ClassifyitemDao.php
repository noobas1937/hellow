<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/30 0030
 * Time: 下午 5:10
 */

namespace gyo2o\dao;


use think\Model;

class ClassifyitemDao extends Model
{
    protected $table = 'tb_classify_item';
    //获取分类下item id
    public function getItemIds($tagId)
    {
        return $this->where('classify_id',$tagId)->where('del_flag',0)->column('item_id');
    }

    //根据itemid获取商品所在分类
    public function getClassifyByItemid($itemid){
        return $this->where('item_id',$itemid)->where('del_flag',0)->column('classify_id');
    }

    /**
     * 获取某类商品(已经区分是否已经下架，是属于未下架的)
     * @return mixed
     */
    public function get_id_list($cateId)
    {
        return $this->where(array('classify_id' => $cateId,'del_flag'=> 0))->select();
    }

}