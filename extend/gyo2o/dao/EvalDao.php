<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6 0006
 * Time: 下午 2:07
 */

namespace gyo2o\dao;


use think\Model;

class EvalDao extends Model
{
    protected $table = 'tb_eval';
    const COMMENT = 0;
    const REPLY = 1;
    const GOODS = 0;
    const UN_DEL = 0;


    public function get_by_item($id,$page_size)
    {
        $map = array(
            'type' => 0,
            'rel_type' => 0,
            'rel_id' => array('in',$id),
            'del_flag'=>self::UN_DEL,
        );
        return $this->where($map)->order('create_date desc')->paginate($page_size)->toArray();
    }

    /**
     * 根据商品评价id获取商品评论回复
     * @Date:2016/08/03
     * @param $id
     * @return mixed
     */
    public function get_by_replay($id)
    {
        $map = array(
            'type' => self::REPLY,
            'rel_type' => self::GOODS,
            'rel_id' => $id,
            'del_flag'=>self::UN_DEL,
        );
        return $this->where($map)->order('create_date desc')->select();
    }

    public function getTotal($where,$ids){
        return $this->where($where)->where('del_flag',self::UN_DEL)->where('rel_type',0)->where('rel_id',$ids)->count();
    }

    public function getEvalsByItemId($where, $sort, $order, $offset, $limit,$ids){
        return $this->where($where)->where('del_flag',self::UN_DEL)->where('rel_type',0)->where('rel_id',$ids)->select();
    }

    public function getContentById($id){
        return $this->where('id',$id)->value('content');
    }

    public function getReplyContentById($id){
        return $this->where('rel_id',$id)->where('type',1)->where('del_flag',0)->value('content');
    }

    public function getReplayById($id){
        return $this->where('rel_id',$id)->where('type',1)->where('del_flag',0)->find();
    }

    /**
     * 评论是否存在
     * @param $id
     *
     * @return bool
     */
    public function exist($id)
    {
        $data = $this->where('id',$id)->find()->toArray();
        $exist = $data && is_array($data);
        return $exist;
    }

    /**
     * 根据用户id获取全部评价
     * @param $userid
     *
     * @return bool
     */
    public function get_by_msg($userid,$page,$pageSize)
    {
        $map = array(
            'type' => self::COMMENT,
            'user_id' => $userid
        );
        return $this->where($map)->order('create_date desc')->limit(($page-1)*$pageSize,$pageSize)->select();
    }


    public function getCountByUserId($userid){
        $map = array(
            'type' => self::COMMENT,
            'user_id' => $userid
        );
        return $this->where($map)->count();
    }

}