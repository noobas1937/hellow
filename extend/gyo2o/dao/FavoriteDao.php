<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6 0006
 * Time: 下午 2:07
 */

namespace gyo2o\dao;


use think\Model;

class FavoriteDao extends Model
{
    const GOODS = 1;
    protected $table = 'tb_favorite';

    /**
     * 获取一条数据
     * @param $map
     *
     * @return mixed
     */
    private function get_one($map)
    {
        return $this->where($map)->find();
    }
    /**
     * 判断用户是否已经对商品进行了收藏
     * @param $uid
     * @param $itemId
     *
     * @return bool
     */
    public function collection($uid, $itemId)
    {
        $map = array(
            'user_id' => $uid,
            'favorite_type' => self::GOODS,
            'rel_id' => $itemId,
        );
        $data = $this->get_one($map);
        return $data;
    }

    public function get_list($uid,$page,$page_size)
    {
        $map = array(
            'user_id' => $uid,
            'favorite_type' => 1
        );
        return $this->where($map)->page($page)->paginate($page_size)->toArray();
    }

}