<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/30 0030
 * Time: 下午 5:06
 */

namespace gyo2o\model;


use gyo2o\BaseModel;
use gyo2o\dao\attachmentDao;
use gyo2o\dao\ClassifyitemDao;
use gyo2o\dao\ItemDao;
use gyo2o\dao\OrderDao;
use gyo2o\dao\PackageItemDao;
use gyo2o\dao\ProductDao;
use gyo2o\dao\ItemImgDao;
use gyo2o\dao\TborderDao;

class Item extends BaseModel
{
    //根据分类获取商品
    public function getByTag($where,$sort,$order,$offset,$limit,$tagid)
    {

        $classifyItemDao = new ClassifyitemDao();
        $itemDao = new ItemDao();
        $itemids = $classifyItemDao->getItemIds($tagid);
        if(!$itemids){
            return ['total'=>0,'rows'=>[]];
        }
        $itemList = $itemDao->getByIds($where,$sort,$order,$offset,$limit,$itemids);
        //添加商品名
        array_walk($itemList,[$this,'addtionProductName']);
        $total = $itemDao->getTotal($where,$itemids);
        return ['total'=>$total,'rows'=>$itemList];
    }

    //根据Itemid,classigyid 删除分类下商品
    public function delClassigyItem($itemid,$classifyid){
        $classifyItemDao = new ClassifyitemDao();
        return $classifyItemDao->where(['classify_id'=>$classifyid,'item_id'=>$itemid])->setField('del_flag',1);
    }

    //商品列表
    public function getItems($where,$sort,$order,$offset,$limit){
        $itemDao = new ItemDao();
        $total = $itemDao->where($where)->where('del_flag',0)->count();
        if(!$total){
            return ['total'=>0,'rows'=>[]];
        }
        $list = $itemDao->getItems($where,$sort,$order,$offset,$limit);
        //添加商品名
        array_walk($list,[$this,'addtionProductName']);
        return ['total'=>$total,'rows'=>$list];
    }

    //添加商品名
    public function addtionProductName(&$item){
        $productDao = new ProductDao();
        $item['name'] =  $productDao->where('id',$item['product_id'])->value('item_nm');
    }


    //删除Item（软删除）
    public function delByItemid($itemid){
        $itemDao = new ItemDao();
        return $itemDao->where('id',$itemid)->setField('del_flag',1);
    }

    /**
     * 处理热卖数据
     * @param $param
     * @param $action
     * @return array|string
     */
    public function get_list($param,$action){
        $itemDao = new ItemDao();
        $city_id = $this->city_id;
        $page_size = $this->page_size;
        if(isset($param['type']) && $param['type'] == 1){
            $page_size = 30;
        }
        $result = $itemDao->$action($city_id,$page_size);
        if(isset($param['type']) && $param['type'] == 1){
            shuffle($result['data']);
            $result['data'] = array_slice($result['data'], 0, 2);
        }
        if($result && is_array($result)){
            foreach($result['data'] as $key => $val){
                $result['data'][$key]['img_url'] = $this->get_item_cover($val['product_id']);
            }


            return $result;
        }else{
            return '暂无数据';
        }
    }


    /**
     * 处理产品图片
     * @param $product_id
     * @return array|false|\PDOStatement|string|\think\Model
     */
    private function get_item_cover($product_id)
    {
        $ItemImg = new ItemImgDao();
        $att = new attachmentDao();
        $img_info = $ItemImg->get_item_cover($product_id);
        return $att->getUrlAttr($img_info['img_id']);
    }

    /**
     * 处理商品详细数据
     * @param $param
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function get_item_detail($param){
        $itemDao = new ItemDao();

        $result = $itemDao->get_bt_item_id($param['item_id']);
        if($result){
            $Img = new ItemImgDao();
            $result['img'] = $Img->get_by_item($result['product_id']);
            $att = new attachmentDao();
            foreach($result['img'] as $key => $val){
                $val['img_url'] = $att->getUrlAttr($val['img_id']);
            }
            $result['cover_img'] = $this->get_item_cover($result['product_id']);
            $result['contentImg'] = $Img->get_item_content($result['product_id']);
            foreach($result['contentImg'] as $key => $val){
                $val['img_url'] = $att->getUrlAttr($val['img_id']);
            }

            $Order = new TborderDao();
            $saleNum = $Order->count_item_sale($param['item_id']);
            $result['sale'] = (int)($saleNum ? $saleNum + $result['sale_num'] : $result['sale_num']);

            $f = new Favorite();
            $result['collection'] = $f->collection($param['user_id'], $param['item_id']) ? 1 : 0;

            $packageItem = new PackageItemDao();
            $packItem = $packageItem->get_by_item($param['item_id']);
            if ($packItem) {
                $result['package'] = $packageItem->get_by_package($packItem['package_id']);
            }
            return $result;
        }else{
            return '暂无数据';
        }

    }

    /**
     * 根据商品分类id获取商品列表
     * @param $param
     * @return mixed
     */
    public function get_cate_list($param)
    {
        $cate = new Classify();
        //根据分类数据返回商品id数组
        $itemIds = $cate->get_id_list($param['cate_id']);
        $data = array();
        if ($itemIds && is_array($itemIds)) {
            $item = new ItemDao();
            $img = new ItemImgDao();
            $att = new attachmentDao();
            //根据商品id数组获取未下架商品详情
            $data = $item->get_list_items($itemIds, $this->page, $this->page_size,$this->city_id);
            if ($data['data'] && is_array($data['data'])) {
                foreach ($data['data'] as $key => $val) {
                    $data['data'][$key]['cover'] = $img->get_item_cover($val['product_id']);
                    $data['data'][$key]['img_url'] = $att->getUrlAttr($data['data'][$key]['cover']['img_id']);
                    //加入一个力省多少钱
                    $data['data'][$key]['lisheng']=$data['data'][$key]['price_original']-$data['data'][$key]['price_single'];
                }

            }

        }
        return $data;
    }
}