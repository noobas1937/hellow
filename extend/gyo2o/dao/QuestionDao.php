<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/13 0013
 * Time: 下午 1:58
 */

namespace gyo2o\dao;


use think\Model;

class QuestionDao extends Model
{
    protected $table = "tb_topic_question";
    private $rec_yn = ['不推荐','推荐'];
    private $typetext = ['健康问答','其它'];
    protected $append = ['rec_yn_text','type_text'];
    const HEALTH = 0;
    const RECOMMEND = 1;

    public function getRecyntextAttr($value,$data){
        return $this->rec_yn[$data['rec_yn']];
    }

    public function getTypetextAttr($value,$data){
        return $this->typetext[$data['type']];
    }

    public function getRecyn(){
        return $this->rec_yn;
    }

    public function getType(){
        return $this->typetext;
    }

    public function get_by_userid($userid, $first = 0, $last = 1)
    {
        if ($userid)
            $where['create_by'] = $userid;
        $this->limit($first, $last);
        return $this->order('create_date desc')->select();
    }

    /**
     * 根据用户id获取用户的所有提问
     * @Author: fuhaijuan
     * @Date: 2016/8/11
     *
     * @param $uid
     *
     * @return mixed
     */
    public function get_health_list($uid)
    {
        $map = array(
            'create_by' => $uid,
            'type' => self::HEALTH,
            //'status'=>'' //问题状态
        );
        return $this->where($map)->order('create_date desc')->select();
    }

    /**
     * 分页获取最新问题
     * @Author: fuhaijuan
     * @Date: 2016/8/11
     *
     * @param $page
     * @param $pageSize
     *
     * @return mixed
     */
    public function get_last($page, $pageSize)
    {
        $map = array(
            'type' => self::HEALTH,
            //'status'=>'' //问题状态
        );
        return $this->where($map)->order('create_date desc')->page($page, $pageSize)->select();
    }

    /**
     * 根据问题类型和用户id分页查询问题
     * @param $userId
     * @param $type
     * @param $page
     * @param $pageSize
     *
     * @return mixed
     */
//    public function get($userId, $type, $page, $pageSize)
//    {
//        $map = array(
//            'create_by' => $userId,
//            'type' => $type,
//        );
//        return $this->where($map)->page($page, $pageSize)->order('create_date desc')->select();
//    }

    /**
     * 根据用户id和提问id判断提问是否存在
     * @param $userId
     * @param $id
     *
     * @return mixed
     */
    public function exist($userId, $id)
    {
        $map = array(
            'create_by' => $userId,
            'id' => $id,
        );
        return $this->where($map)->select();
    }

    /**
     * 添加问题
     * @param $title
     * @param $question
     * @param $type
     *
     * @return mixed
     */
    public function add_question($title, $question, $type, $userid, $create_date)
    {
        $data = array(
            'title' => $title,
            'question' => $question,
            'type' => $type,
            'create_date' => date('Y-m-d H:i:s'),
            'create_by' => $userid,
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => $userid,
        );
        if (false != $create_date) {
            $data['create_date'] = $create_date;
            $data['update_date'] = $create_date;
        }


        return $this->add($data);
    }

    /**
     * 获取首页精华问答
     * @return mixed
     */
    public function get_topic_question()
    {
        $map = array(
            'rec_yn' => self::RECOMMEND,
            'type' => self::HEALTH,
        );
        //$map['id'] = 66;

        return $this->where($map)->order('create_date desc')->select();
    }
}