<?php

namespace gyo2o\model;

use gyo2o\BaseModel;
use gyo2o\dao\attachmentDao;
use gyo2o\dao\FavoriteDao;
use gyo2o\dao\ItemDao;
use gyo2o\dao\ItemImgDao;

class Favorite extends BaseModel
{

    /**
     * 判断用户是否已经对商品进行了收藏
     * @param $uid
     * @param $itemId
     *
     * @return bool
     */
    public function collection($uid, $itemId)
    {
        $Favorite = new FavoriteDao();
        return $Favorite->collection($uid, $itemId);
    }

    public function add_favorites($param)
    {
        $Favorite = new FavoriteDao();
        if($Favorite->collection($param['user_id'], $param['item_id']))
            {
                return '重复收藏';
            }
        $data = array(
            'user_id' => $param['user_id'],
            'favorite_type' => 1,
            'rel_id' => $param['item_id'],
            'favorite_date' => date('Y-m-d H:i:s')
        );
        $result = $Favorite->insert($data);
        return $result;
    }

    public function remove_favorites($param){
        $Favorite = new FavoriteDao();
        $map = array(
            'user_id' => $param['user_id'],
            'rel_id' => $param['item_id'],
            'favorite_type' => 1
        );
        return $Favorite->where($map)->delete();
    }

    /**
     * 我的收藏
     * @param $param
     * @return array
     */
    public function get_list($param)
    {
        $favorite = new FavoriteDao();
        $item = new ItemDao();
        $itemimg = new ItemImgDao();
        $att = new attachmentDao();
        $favorite_data = $favorite->get_list($param['user_id'],$this->page,$this->page_size);
        foreach ($favorite_data['data'] as $key => $val) {
            $favorite_data['data'][$key] = $item->get_bt_item_id($val["rel_id"]);
            $favorite_data['data'][$key]['img'] = $itemimg->get_item_cover($favorite_data['data'][$key]['product_id']);
            $favorite_data['data'][$key]['img_url'] = $att->getUrlAttr($favorite_data['data'][$key]['img']['img_id']);
        }
        return $favorite_data;
    }

}
