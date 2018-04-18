<?php
/**
 * Created by PhpStorm.
 * User: Administrator

 * Date: 2017/12/7 0007
 * Time: 下午 2:47
 */

namespace gyo2o\dao;


use think\Model;

class ItemImgDao extends Model
{
    protected $table = 'tb_item_img';


    /**
     * 根据商品id获取图片信息
     * @Author: fuhaijuan
     * @Date:2016/08/03
     * @param $itemId
     *
     * @return mixed
     */
    public function get_by_item($itemId)
    {
        return $this->where(array('item_id' => $itemId, 'type' => 2))->order('seq asc')->select();
    }


    /**
     * 获取商品封面
     * @Author: fuhaijuan
     * @Date:2016/08/03
     * @param $itemId
     *
     * @return mixed

     */
    public function get_item_cover($itemId)
    {
        $cover = $this->where(array('item_id' => $itemId, 'type' => 1))->find();
        return $cover;
    }



    /**
     * 获取商品内容图片
     * @Author: fuhaijuan
     * @Date:2016/08/03
     * @param $itemId
     *
     * @return mixed
     */
    public function get_item_content($itemId)
    {
        return $this->where(array('item_id' => $itemId, 'type' => 3))->order('seq asc')->select();
    }

    //修改产品图片
    public function edit_image($oldImgs,$newImgs){
        $oldImgsBreakStep = 0;
        $this->startTrans();
        foreach ($oldImgs as $k=>$oldImg){
            if(empty($newImgs)){
                $oldImgsBreakStep = $k;
                break;
            }
            $seq = $oldImg['seq'];
            $oldImg->img_id = $newImgs[$seq]['img_id'];
            if($oldImg->save()===false){
                return false;
            }
            unset($newImgs[$seq]);
        }

        if($oldImgsBreakStep){
            //旧的多了
            for($i = $oldImgsBreakStep;$i<count($oldImgs);$i++){
                if(!$oldImgs[$i]->delete()){
                    return false;
                }
            }
        }

        if(!empty($newImgs)){
            //新的多了
            if(!$this->saveAll($newImgs)){
                return false;
            }
        }

        $this->commit();
        return true;

    }

}