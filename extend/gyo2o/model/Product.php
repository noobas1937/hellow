<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6 0006
 * Time: 下午 5:57
 */

namespace gyo2o\model;


use gyo2o\BaseModel;
use gyo2o\dao\attachmentDao;
use gyo2o\dao\ItemImgDao;
use gyo2o\dao\ProductDao;
use think\Config;

class Product extends BaseModel
{
    public function getProducts($where,$sort,$order,$offset,$limit)
    {
        $productDao = new ProductDao();
        $total = $productDao->getTotal($where);
        if(empty($total)){
            return ['total'=>0,'rows'=>[]];
        }
        $rows = $productDao->getProducts($where,$sort,$order,$offset,$limit);
        return ['total'=>$total,'rows'=>$rows];
    }


    public function getById($id){
        $productDao = new ProductDao();
        $product = $productDao->getById($id);
        $this->parseProductImg($product);
        $this->parseProductCoverImg($product);
        $this->parseProductContentImg($product);
        return $product;
    }

    //添加修改封面图片
    public function modifyCoverImg($coverImg,$itemid)
    {
        $attachmentDao = new attachmentDao();
        $domain = Config::get('qiniu.domain');
        $file = substr(strstr($coverImg,$domain),strlen($domain)+1);
        $id = $attachmentDao->where('url',$file)->value('id');
        $itemImgDao = new ItemImgDao();
        $row = $itemImgDao->get_item_cover($itemid);
        if($row){
            $row['img_id'] = $id;
            return $row->save();
        }else{
            return $itemImgDao->insert(['img_id'=>$id,'item_id'=>$itemid,'type'=>1]);
        }
    }

    //添加修改商品图片
    public function modifyImg($img,$itemid,$type){
        $attachment = new attachmentDao();
        $imgs = explode(',',$img);
        $imgIds = $this->imagesToIds($imgs,$itemid,$type);
        $itemImageDao = new ItemImgDao();
        if($type==2){
            $oldImgs = $itemImageDao->get_by_item($itemid);
        }elseif($type==3){
            $oldImgs = $itemImageDao->get_item_content($itemid);
        }

        if(!empty($oldImgs)){
            //修改
            return $itemImageDao->edit_image($oldImgs,$imgIds);
        }else{
            //添加

            return $itemImageDao->saveAll($imgIds);
        }

    }

    //新增产品
    public function addOne($param){
        $img = $param['img'];
        $contentImg = $param['contentimg'];
        $coverImg = $param['coverimg'];
        $param['create_date'] = date('Y-m-d H:i:s');
        $param['update_date'] = date('Y-m-d H:i:s');
        $productDao = new ProductDao();
        unset($param['img']);
        unset($param['contentimg']);
        unset($param['coverimg']);
        if(!$productDao->save($param)){
            return false;
        }
        $itemid = $productDao->id;
        return $this->modifyImg($img,$itemid,2)&&$this->modifyImg($contentImg,$itemid,3)&&$this->modifyCoverImg($coverImg,$itemid);

    }

    private function parseProductImg(&$row){
        $itemImgDao = new ItemImgDao();
        //产品图片
        $imgs = $itemImgDao->get_by_item($row['id']);
        $attachment = new attachmentDao();
        $imgSet = [];
        foreach ($imgs as $img){
            $imgSet[] = $attachment->getUrlAttr($img['img_id']);
        }
        $row['img'] = implode(',',$imgSet);
    }

    private function parseProductCoverImg(&$row){
        $itemImgDao = new ItemImgDao();
        //封面图片
        $img = $itemImgDao->get_item_cover($row['id']);
        $attachment = new attachmentDao();
        $row['coverimg'] = $attachment->getUrlAttr($img['img_id']);

    }

    private function parseProductContentImg(&$row){
        $itemImgDao = new ItemImgDao();
        //产品图片
        $imgs = $itemImgDao->get_item_content($row['id']);
        $attachment = new attachmentDao();
        $imgSet = [];
        foreach ($imgs as $img){
            $imgSet[] = $attachment->getUrlAttr($img['img_id']);
        }
        $row['contentimg'] = implode(',',$imgSet);
    }

    private function imagesToIds($imgs,$itemid,$type){
        $attachmentDao = new attachmentDao();
        $domain = Config::get('qiniu.domain');
        $imgIds = [];
        $i = 1;
        foreach ($imgs as $img){
            $file = substr(strstr($img,$domain),strlen($domain)+1);
            $id = $attachmentDao->where('url',$file)->value('id');
            $imgIds[$i] = ['seq'=>$i,'img_id'=>$id,'item_id'=>$itemid,'type'=>$type];
            $i++;
        }
        return $imgIds;
    }

}