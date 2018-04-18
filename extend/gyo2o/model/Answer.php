<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/13 0013
 * Time: 下午 3:47
 */

namespace gyo2o\model;


use gyo2o\BaseModel;
use gyo2o\dao\QuestionDao;
use gyo2o\dao\AnswerDao;


class Answer extends BaseModel
{

    const DIETITIAN = 1;
    const NOT_DIETITIAN = 0;
    const ANSWER = 2;

    /**
     * 分页获取最佳答案
     * @param $page
     * @param $pageSize
     *
     * @return array|mixed
     */
    public function get_best($page, $pageSize)
    {
        $answer = new AnswerDao();
        $bestAnswer = $answer->get_best($page, $pageSize);
        if ($bestAnswer && is_array($bestAnswer)) {
            $question = new QuestionDao();
            $user = new UserModel();
            $topicImg = new TopicImgDao();
            $favoriteRecord = new FavoriteRecordModel();
            $cust = new UserDao();
            foreach ($bestAnswer as $key => $val) {
                $bestAnswer[$key]['question'] = $question->getById($val['topic_id']);
                if($bestAnswer[$key]['question']){
                    $bestAnswer[$key]['favorite'] = $favoriteRecord->favorite($this->user_id, $val['id']);
                    $bestAnswer[$key]['user_info'] = $user->get_by_userid($val['create_by']);
                    if($bestAnswer[$key]['user_info']['id'] == false){
                        $head_img = $bestAnswer[$key]['user_info']['head_img'];
                        $bestAnswer[$key]['user_info'] = $cust->get_by_id($val['create_by']);
                        $bestAnswer[$key]['user_info']['head_img'] = $head_img;
                    }
                    $bestAnswer[$key]['imgs'] = $topicImg->get_by_topicId($val['id'], self::ANSWER);
                }else{
                    unset($bestAnswer[$key]);
                }
            }
            return $bestAnswer;
        }
        return array();
    }

    /**
     * 获取用户id的回答
     * @param $userId
     * @param $page
     * @param $pageSize
     *
     * @return array
     */
    public function get($userId, $page, $pageSize)
    {
        $answer = new AnswerDao();
        $data = $answer->get($userId, $page, $pageSize);
        if ($data && is_array($data)) {
            $question = new QuestionDao();
            $user = new UserModel();
            $topicImg = new TopicImgDao();
            $favoriteRecord = new FavoriteRecordModel();
            foreach ($data as $key => $val) {
                $data[$key]['favorite'] = $favoriteRecord->favorite($this->user_id, $val['id']);
                $data[$key]['question'] = $question->getById($val['topic_id']);
                $data[$key]['user_info'] = $user->get_by_userid($val['create_by']);
                $data[$key]['imgs'] = $topicImg->get_by_topicId($val['id'], self::ANSWER);
            }
            return $data;
        }
        return array();
    }

    /**
     * 根据答案id获取回答
     * @param $id
     *
     * @return mixed
     */
    public function get_by_id($id)
    {
        $answer = new AnswerDao();
        $topicImg = new TopicImgDao();
        $data = $answer->getById($id);
        if ($data)
            $data['imgs'] = $topicImg->get_by_topicId($data['id'], self::ANSWER);
        return $data;
    }

    /**
     * 根据用户id和回答id检查回答是否存在
     * @param $id
     * @param $uid
     *
     * @return bool
     */
    public function exist($id, $uid)
    {
        $answer = new AnswerDao();
        $data = $answer->exist($uid, $id);
        $exist = $data && is_array($data);
        return $exist;
    }

    /**
     * 根据问题id获取问题的所有回答
     * @param $topicId
     *
     * @return array
     */
    public function get_by_question($topicId)
    {
        $answer = new AnswerDao();
        $data = $answer->get_by_topic($topicId);
        if ($data && is_array($data)) {
            $user = new UserModel();
            $u = new UserDao();
            $role = new RoleUserModel();
            $topicImg = new TopicImgDao();
            $f = new FavoriteRecordModel();
            foreach ($data as $key => $val) {
                $data[$key]['is_dietitian'] = $role->is_dietitian($val['create_by']) ? self::DIETITIAN : self::NOT_DIETITIAN;
                if ($data[$key]['is_dietitian'])
                    $data[$key]['user_info'] = $u->get_by_id($val['create_by']);
                else
                    $data[$key]['user_info'] = $user->get_by_userid($val['create_by']);
                $data[$key]['imgs'] = $topicImg->get_by_topicId($val['id'], self::ANSWER);
                $data[$key]['favorite'] = $f->favorite($this->user_id, $val['id']);
            }

            return $data;
        }
        return array();
    }

    /**
     * 根据问题获取回答问题的用户信息
     * @param $topicId
     *
     * @return array
     */
    public function get_headimg_by_question($topicId)
    {
        $answer = new AnswerDao();
        $data = $answer->get_by_topic($topicId);
        $arr = array();
        if ($data && is_array($data)) {
            //$user = new UserInfoDao();
            $user = new UserModel();
            foreach ($data as $key => $val) {
                $arr[] = $user->get_by_userid($val['create_by']);
            }
        }
        return $arr;
    }

    /**
     * 获取指定问题的最佳回答和最新回答
     * @param $topicId
     *
     * @return mixed
     */
    public function get_best_and_last($topicId)
    {
        $answer = new AnswerDao();
        $data['best'] = $answer->get_best_by_topic($topicId);
        if ($data['best']) {
            $f = new FavoriteRecordModel();
            $data['best']['favorite'] = $f->favorite($this->user_id, $data['best']['id']);
        }
        $data['last'] = $answer->get_last_by_topic($topicId);
        if ($data['last']) {
            $f = new FavoriteRecordModel();
            $data['last']['favorite'] = $f->favorite($this->user_id, $data['last']['id']);
        }

        if ($data['best']['id'] == $data['last']['id'])
            unset($data['last']);

        if ($data && is_array($data)) {
            $user = new UserModel();
            $cyUserDao = new UserDao();
            $role = new RoleUserModel();
            $topicImg = new TopicImgDao();
            foreach ($data as $key => $val) {
                if ($val) {
                    $data[$key]['imgs'] = $topicImg->get_by_topicId($val['id'], self::ANSWER);
                    $data[$key]['is_dietitian'] = $role->is_dietitian($val['create_by']) ? self::DIETITIAN : self::NOT_DIETITIAN;
                    $data[$key]['user_info'] = $user->get_by_userid($val['create_by']);
                    if ($data[$key]['is_dietitian'])
                        $data[$key]['cy_user']['username'] = $cyUserDao->get_by_id($val['create_by']);
                }

            }
            return $data;
        }
        return array();
    }

    public function add($topicId, $ans, $create_date = false)
    {
        $answer = new AnswerDao();
        return $answer->add_answer($topicId, $ans, $this->user_id,$create_date);
    }

    public function edit($topicId, $ans, $id)
    {
        $answer = new AnswerDao();
        return $answer->edit_answer($topicId, $ans, $id, $this->user_id);
    }


    /**
     * 删除回答
     * @param $topicId
     *
     * @return array
     */
    public function del($topicId)
    {
        if ($topicId && is_numeric($topicId) && 0 < $topicId && $this->exist($topicId, $this->user_id)) {
            $answer = new AnswerDao();
            if ($answer->where(array('id' => $topicId))->delete()) {
                $topicImg = new TopicImgModel();
                $topicImg->del($topicId, self::ANSWER);
                return array('status' => 'success', 'code' => 100000, 'msg' => '删除成功', 'output' => null);
            }
            return array('status' => 'failer', 'code' => 300000, 'msg' => '删除失败', 'output' => null);
        }
        return array('status' => 'failer', 'code' => 200000, 'msg' => '参数错误', 'output' => null);
    }

    /**
     * 取消最佳答案
     * @param $id
     *
     * @return bool
     */
    public function cancel_best($id)
    {
        $answer = new AnswerDao();
        return $answer->cancel_best($id);
    }

    public function getAnswerByQuestion($questionid,$where, $sort, $order, $offset, $limit){
        $answerDao = new AnswerDao();
        $total = $answerDao->getTotal($questionid,$where);
        if(empty($total)){
            return ['total'=>$total,'rows'=>[]];
        }

        $rows = $answerDao->getAnswerByQuestion($questionid,$where, $sort, $order, $offset, $limit);
        return ['total'=>$total,'rows'=>$rows];
    }
}