<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/19 0019
 * Time: 下午 3:27
 */

namespace gyo2o\dao;


use think\Model;

class SearchRecordDao extends Model
{
    protected $table = "tb_search_record";
    const UN_DEL = 0;
    protected $_auto = array(
        array('del_flag', self::UN_DEL),
    );

    /*
     * 根据用户id获取用户的历史搜索
     * @Author: fuhaijuan
     * @Date:2016/08/03
     * */
    public function get_my_history($uid)
    {
        $map = array(
            'user_id' => $uid,
            'del_flag' => self::UN_DEL
        );
        return $this->where($map)->group('key_word')->order('search_date desc')->limit('0,16')->select();
    }

    /*
     * 根据用户id删除用户的历史搜索记录
     * @Author: fuhaijuan
     * @Date:2016/08/03
     * */
    public function del_all_by_uid($uid)
    {
        $map = array(
            'user_id' => $uid,
            'del_flag' => self::UN_DEL
        );
        return $this->where($map)->delete();
    }
}