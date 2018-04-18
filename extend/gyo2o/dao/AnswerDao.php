<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/13 0013
 * Time: 下午 3:39
 */

namespace gyo2o\dao;


use think\Model;

class AnswerDao extends Model
{
    protected $table = "tb_topic_answer";
    private $best_yn = ['否','是'];
    private $r_yn = ['未读','已读'];
    protected  $append = ['best_yn_text','r_yn_text'];
    const BEST = 1;
    const NOT_BEST = 0;
    const READ = 1;
    const UN_READ = 0;
    const FAVORITE = 1;
    const UN_FAVORITE = 0;

    public function getBestyntextAttr($value,$data){
        return $this->best_yn[$data['best_yn']];
    }

    public function getRyntextAttr($value,$data){
        return $this->r_yn[$data['r_yn']];
    }

    public function getBestyn(){
        return $this->best_yn;
    }

    public function getRyn(){
        return $this->r_yn;
    }

    /**
     * 分页获取最佳答案
     * @param $page
     * @param $pageSize
     *
     * @return mixed
     */
    public function get_best($page, $pageSize)
    {
        $map = array(
            'best_yn' => self::BEST,
        );
        $data = $this->where($map)->page($page, $pageSize)->order('create_date desc')->select();
        return $data;
    }

    /**
     * 获取用户id的回答
     * @param $userId
     * @param $page
     * @param $pageSize
     *
     * @return mixed
     */
//    public function get($userId, $page, $pageSize)
//    {
//        $data = $this->where(array('create_by' => $userId))->page($page, $pageSize)->order('create_date desc')->select();
//        return $data;
//    }

    /**
     * 根据用户id和回答id判断回答是否存在
     * @param $userId
     * @param $id
     *
     * @return mixed
     */
    public function exist($userId, $id)
    {
        $map = array(
            'create_by' => $userId,
            'id' => $id
        );
        return $this->where($map)->select();
    }

    /**
     * 设置已读
     * @param $id
     *
     * @return bool
     */
    public function set_read($id)
    {
        return $this->where(array('id' => $id))->setField('r_yn', self::READ);
    }


    /**
     * 根据问题找回答
     * @param $topicId
     *
     * @return mixed
     */
    public function get_by_topic($topicId)
    {
        $data = $this->where(array('topic_id' => $topicId))->order('best_yn desc,create_date desc')->select();
//        $data = $this->is_favorite($data);
        return $data;
    }

    /**
     * 获取问题的回答数量
     * @Author: fuhaijuan
     * @Date: 2016/8/11
     * @param $question_id
     *
     * @return mixed
     */
    public function count_answer($question_id)
    {
        return $this->where(array('topic_id' => $question_id))->count();
    }

    /**
     * 获取问题id对应的最佳回答
     * @param $topicId
     *
     * @return mixed
     */
    public function get_best_by_topic($topicId)
    {
        $map = array(
            'topic_id' => $topicId,
            'best_yn' => self::BEST,
        );
        $data = $this->where($map)->find();
        return $data;
    }

    /**
     * 获取问题id对应的最新回答
     * @param $topicId
     *
     * @return mixed
     */
    public function get_last_by_topic($topicId)
    {
        $map = array(
            'topic_id' => $topicId,
        );
        $data = $this->where($map)->order('create_date desc')->find();

        return $data;
    }

    /**
     * 根据回答id设置最佳回答
     * @param $id
     *
     * @return bool
     */
    public function set_best($id)
    {
        return $this->where(array('id' => $id))->setField('best_yn', self::BEST);
    }

    /**
     * 根据回答id取消最佳回答
     * @param $id
     *
     * @return bool
     */
    public function cancel_best($id)
    {
        return $this->where(array('id' => $id))->setField('best_yn', self::NOT_BEST);
    }

    /**
     * 添加回答
     * @param $qId
     * @param $ans
     * @param $userid
     * @return mixed
     */
    public function add_answer($qId, $ans, $userid, $create_date = false)
    {
        $data = array(
            'topic_id' => $qId,
            'answer' => $ans,
            'best_yn' => self::NOT_BEST,
            'r_yn' => self::UN_READ,
            'favorite_count' => 0,
            'create_date' => date('Y-m-d H:i:s'),
            'create_by' => $userid,
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => $userid,
        );

        if (false != $create_date) {
            $data['create_date'] = $create_date;
        }

        return $this->add($data);
    }

    /**
     * 编辑回答
     * @param $qId
     * @param $ans
     * @param $answerId
     *
     * @return bool
     */
    public function edit_answer($qId, $ans, $answerId, $userid)
    {
        $map = array(
            'id' => $answerId,
            'topic_id' => $qId,
            'create_by' => $userid
        );
        $data = array(
            'answer' => $ans,
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => $userid,
        );

        return $this->where($map)->save($data);
    }

    /**
     * getUser 根据答案ID获取回答者ID
     * @param $answerId
     * @return mixed
     */
    public function get_user($answerId){
        $map = array(
            'id'=>$answerId
        );
        return $this->where($map)->getField('create_by');
    }

    public function getTotal($questionid,$where){
        return $this->where('topic_id',$questionid)->where($where)->count();
    }

    public function getAnswerByQuestion($questionid,$where, $sort, $order, $offset, $limit){
        return $this->where('topic_id',$questionid)->where($where)->order($sort,$order)->limit($offset,$limit)->select();
    }
}