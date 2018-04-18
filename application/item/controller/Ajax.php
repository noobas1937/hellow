<?php
/**
 * Created by PhpStorm.
 * User: liuwen
 * Date: 17/7/19
 * Time: 上午9:28
 */

namespace app\item\controller;

use app\common\controller\Api;
use gyo2o\model\Cart;
use gyo2o\model\Classify;
use gyo2o\model\Favorite;
use gyo2o\model\Item;
use gyo2o\model\ItemEval;


class Ajax extends Api
{


    /**
     * 添加购物车
     * @return \think\response\Json
     */
    public function add_cart()
    {
        $param = input('post.');
        $validate = $this->validate($param,'item/ClassifyValidate.cart');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new Cart();
            $result = $class->add_cart($param);
            $result = $this->return_dao($result,'添加成功','添加失败');
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    /**
     * 删除购物车商品
     * @return \think\response\Json
     */
    public function del_cart()
    {
        $param = input('post.');
        $validate = $this->validate($param,'item/ClassifyValidate.cart');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new Cart();
            $result = $class->del_cart($param);
            $result = $this->return_dao($result,'删除成功','删除失败');
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    /**
     * 获取分类列表
     * @return \think\response\Json
     */
    public function class_list(){
        $param = input('post.');
        $validate = $this->validate($param,'item/ClassifyValidate.class_list');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new Classify();
            $result = $class->get_class_list($param);
            $result = $this->return_list($result,'查询成功','暂无数据');
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    /**
     * 热卖商品列表
     * @return \think\response\Json
     */
    public function hot_list(){
        $param = input('post.');
        $validate = $this->validate($param,'item/ClassifyValidate.item_list');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new Item();
            $result = $class->get_list($param,'get_hot_list');
            $result = $this->return_list($result,'查询成功','暂无数据');
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    /**
     * 最新商品列表
     * @return \think\response\Json
     */
    public function newest_list(){
        $param = input('post.');
        $validate = $this->validate($param,'item/ClassifyValidate.item_list');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new Item();
            $result = $class->get_list($param,'get_newest_list');
            $result = $this->return_list($result,'查询成功','暂无数据');
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    /**
     * 商品详情
     * @return \think\response\Json
     */
    public function item_detail(){
        $param = input('post.');
        $validate = $this->validate($param,'item/ClassifyValidate.item_detail');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new Item();
            $result = $class->get_item_detail($param);
            $result = $this->return_dao($result,'查询成功','暂无数据');
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    /**
     * 商品全部评价
     * @return \think\response\Json
     */

    public function item_eval(){
        $param = input('post.');
        $validate = $this->validate($param,'item/ClassifyValidate.item_detail');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new ItemEval();
            $result = $class->get_item_eval($param);
            $result = $this->return_list($result,'查询成功','暂无数据');
        }else{
            $result = $return_validate;
        }
        return json($result);
    }


    /**
     * 购物车列表
     * @return \think\response\Json
     */
    public function cart_list(){
        $param = input('post.');
        $validate = $this->validate($param,'item/ClassifyValidate.cart_list');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new Cart();
            $result = $class->get_by_card($param);
            $result = $this->return_list($result,'查询成功','暂无数据');
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    /**
     * 单个分类列表
     * @return \think\response\Json
     */
    public function cate_list(){
        $param = input('post.');
        $validate = $this->validate($param,'item/ClassifyValidate.cate_list');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new Item();
            $result = $class->get_cate_list($param);
            $result = $this->return_list($result,'查询成功','暂无数据');
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    /**
     * 添加收藏
     * @return \think\response\Json
     */
    public function add_favorites(){
        $param = input('post.');
        $validate = $this->validate($param,'item/ClassifyValidate.cart');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new Favorite();
            $result = $class->add_favorites($param);
            $result = $this->return_dao($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    /**
     * 取消收藏
     * @return \think\response\Json
     */
    public function remove_favorites(){
        $param = input('post.');
        $validate = $this->validate($param,'item/ClassifyValidate.cart');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new Favorite();
            $result = $class->remove_favorites($param);
            $result = $this->return_dao($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    /**
     * 收藏列表
     * @return \think\response\Json
     */
    public function favorites_list(){
        $param = input('post.');
        $validate = $this->validate($param,'item/ClassifyValidate.cart_list');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $class = new Favorite();
            $result = $class->get_list($param);
            $result = $this->return_list($result);
        }else{
            $result = $return_validate;
        }
        return json($result);
    }
}
