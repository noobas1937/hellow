<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/30 0030
 * Time: 下午 5:08
 */

namespace gyo2o\dao;


use think\Db;
use think\Model;

class EvalImgDao extends Model
{
    protected $table = 'tb_eval_img';

    /**
     * 根据评论id获取图片信息
     * @Author: fuhaijuan
     * @Date:2016/08/03
     * @param $id
     *
     * @return mixed
     */
    public function get_by_eval($id)
    {
        $data = $this->where(array('eval_id' => $id))->select();
        return $data;
    }

    public function getEvalImgIds($evalid){
        return $this->where('eval_id',$evalid)->column('img_id');
    }

}