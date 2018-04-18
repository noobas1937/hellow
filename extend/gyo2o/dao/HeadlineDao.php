<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7 0007
 * Time: 下午 5:57
 */

namespace gyo2o\dao;

use think\Model;

class HeadlineDao extends Model
{
    // 表名
    protected $table = 'tb_headline';

    /**
     * 根据城市获取头条数据
     * @param $city_id
     * @param int $page_size
     * @return array
     */
    public function get_by_hot($city_id,$page_size = 5){
        $where = [
            'city_id' => $city_id
        ];
        return $this->where($where)->order('seq desc')->paginate($page_size)->toArray();
    }

    public function getTotal($where){
        return $this->where($where)->count();
    }

    public function getHeadlines($where,$sort,$order,$offset,$limit){
        return $this->where($where)->order($sort,$order)->limit($offset,$limit)->select();
    }
}
