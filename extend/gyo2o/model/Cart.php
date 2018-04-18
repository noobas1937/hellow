<?php

namespace gyo2o\model;

use gyo2o\BaseModel;
use gyo2o\dao\attachmentDao;
use gyo2o\dao\CartDao;
use gyo2o\dao\ItemDao;
use gyo2o\dao\ItemImgDao;

class Cart extends BaseModel
{
    /**
     * 添加购物车
     * @param $param
     * @return array|false
     */
    public function add_cart($param){
        $cart = new CartDao();
        $num = isset($param['num']) ? $param['num'] : 1;
        $cart_info = $cart->get_info($param['user_id'],$param['item_id']);
        if($cart_info){
            if($num > 1){
                $result = $cart->set_num($param['user_id'],$param['item_id'],$num);
            }elseif($num == 1){
                $result = $cart->add_num($param['user_id'],$param['item_id']);
            }else{
                $result = false;
            }
        }else{
            $result = $cart->add_cart($param['user_id'],$num,$param['item_id']);
        }
        return $result;
    }

    /**
     * 删除购物车数据
     * @param $param
     * @return array|false
     */
    public function del_cart($param){
        $cart = new CartDao();
        $result = $cart->del_cart($param['user_id'],$param['item_id']);
        return $result;
    }


    /**
     * 查询购物车的商品数据
     * @param $param
     * @return array|false
     */
    public function get_by_card($param)
    {
        $page_size = isset($param['page_size']) ? $param['page_size'] : 5;
        $page = isset($param['page']) ? $param['page'] : 1;

        $card = new CartDao();
        $item = new ItemDao();
        $item_img = new ItemImgDao();

        $result = $card->get_by_user_id($param['user_id'],$page,$page_size);
        $att = new attachmentDao();
        if($result['data'] && is_array($result['data'])){

            foreach($result['data'] as $k => $v) {
                $result['data'][$k]['item'] = $item->get_bt_item_id($v['item_id']);
                $result['data'][$k]['img'] = $item_img->get_item_cover($result['data'][$k]['item']['product_id']);
                $result['data'][$k]['img']['img_url'] = $att->getUrlAttr($result['data'][$k]['img']['img_id']);
                if($result['data'][$k]['item'] == ''){
                    $card->set_flag($v['id'],1);
                    unset($result['data'][$k]);
                }
            }
        }
        return $result;
    }


}
