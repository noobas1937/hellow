<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/19 0019
 * Time: 下午 3:21
 */

namespace gyo2o\model;


use gyo2o\BaseModel;
use gyo2o\dao\attachmentDao;
use gyo2o\dao\ItemDao;
use gyo2o\dao\SearchRecordDao;
use gyo2o\dao\ItemImgDao;
use gyo2o\dao\KeyWordDao;

class Search extends BaseModel
{


    /**
     * 获取用户历史搜索记录
     * @param $uid
     *
     * @return mixed
     */
    public function get_my_history($uid)
    {
        $Search = new SearchRecordDao();
        return $Search->get_my_history($uid);
    }


    /**
     * 删除用户的搜索历史
     * @param $uid
     *
     * @return mixed
     */
    public function del_all_by_uid($uid)
    {
        $Search = new SearchRecordDao();
        return $Search->del_all_by_uid($uid);
    }


    /**
     * 添加搜索记录
     * @param        $words
     * @param string $uid
     *
     * @return bool
     */
    public function add($words, $uid = '')
    {
        $Search = new SearchRecordDao();
        if ($Search->create()) {
            if ($uid)
                $data = array(
                    'user_id' => $uid,
                    'search_date' => date('Y-m-d H:i:s'),
                    'key_word' => $words
                );
            else
                $data = array(
                    'search_date' => date('Y-m-d H:i:s'),
                    'key_word' => $words
                );
            if ($Search->save($data))
                return true;
        }
        return false;
    }


    /**
     * 根据关键词搜索相关商品
     * @param $words
     * @param $p
     *
     * @return mixed
     */
    public function search_item_by_words($words, $p,$userid,$pagesize)
    {
        $Item = new ItemDao();
        $atr = new attachmentDao();
        $Img = new ItemImgDao();
        $keyWord = new KeyWordDao();
        $keyWords = $keyWord->get_all();

        $user_info_status= new UserStatus();
        $user_status = $user_info_status->get_by_userid($userid);
        $wordArr = $this->getArrs($keyWords, 'word');
        $searchArr = $this->get_keys_by_search($words, $wordArr);
        array_push($searchArr, '%' . $words . '%');
        $data['count'] = $Item->count_search_by_title($searchArr,$user_status['city_id']);
        $data['list'] = $Item->search_by_title($searchArr, $p, $pagesize,$user_status['city_id']);
        if ($data['list'] && is_array($data['list']))
            foreach ($data['list'] as $key => $val) {
                $data['list'][$key]['cover'] = $Img->get_item_cover($val['product_id']);
                if(!empty($data['list'][$key]['cover']['img_id'])){
                    $data['list'][$key]['cover']['img_url'] = $atr->getUrlAttr($data['list'][$key]['cover']['img_id']);
                }
            }
        $data['word'] = $words;
        return $data;
    }

    protected function getArrs($data,$key){
        $arr = array();
        if($data && is_array($data))
            foreach($data as $val){
                $arr[] = $val[$key];
            }
        return $arr;
    }

    public function get_keys_by_search($words, $keys)
    {
        $data = array();
        foreach ($keys as $val) {
            if (mb_strpos($words, $val) !== false)
                $data[] = '%' . $val . '%';
        }
        return $data;
    }

}