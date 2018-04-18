<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/14 0014
 * Time: 下午 3:37
 */

namespace gyo2o\dao;


use think\Model;

class KeyWordDao extends Model
{

    protected $table = "tb_key_word";

    /**
     * 获取热门词汇
     * @Author: fuhaijuan
     * @Date:2016/08/03
     * @return mixed
     */
    public function get_hot()
    {
        return $this->field('*,frequency+frequency2 as frequencys')->order('frequencys desc')->limit('0,16')->select();
    }

    /**
     * 获取所有关键词
     * @Author: fuhaijuan
     * @Date:2016/08/03
     * @return mixed
     */
    public function get_all()
    {
        return $this->order('frequency desc')->select();
    }

    /**
     * @Author: fuhaijuan
     * @Date:2016/08/03
     *
     * 根据搜索增加关键词热度
     * @param $words
     */
    public function add_frequency($words)
    {
        $map = array(
            'word' => $words
        );
        $this->where($map)->setInc('frequency');
    }
}