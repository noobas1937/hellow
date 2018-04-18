<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/30 0030
 * Time: 下午 2:21
 */

namespace gyo2o\model;


use gyo2o\BaseModel;
use gyo2o\dao\attachmentDao;
use gyo2o\dao\ClassifyDao;
use gyo2o\dao\ClassifyimgDao;
use gyo2o\dao\ClassifyitemDao;
use think\Config;

class Classify extends BaseModel
{

    public function getClassify($where,$sort,$order,$offset,$limit)
    {
        $classifyDao = new ClassifyDao();
        $total = $classifyDao->getClassifyNumber($where);
        if($total){
            $rows = $classifyDao->getClassifys($where,$sort,$order,$offset,$limit);
            //获取封面和图片
            $classifyImaDao = new ClassifyimgDao();
            $domain = Config::get('qiniu.domain');
            foreach ($rows as &$row){
                $classifyImgs = $classifyImaDao->where('classify_id',$row['id'])->join(['tb_attachment'=>'a', 'tb_'],'a.id = tb_classify_img.img_id')->select();
                if($classifyImgs){
                    foreach ($classifyImgs as $img){
                        if($img['type'] ==1){
                            $icon = $img['url'];
                        }elseif ($img['type'] == 2){
                            $banner = $img['url'];
                        }
                    }
                }

                $row['icon'] = empty($icon)?'':'http://'.$domain.'/'.$icon;
                $row['banner'] = empty($banner)?'':'http://'.$domain.'/'.$banner;
                $icon = '';
                $banner = '';
            }

        }else{
            return ['total'=>0,'rows'=>[]];
        }

        return ['total'=>$total,'rows'=>$rows];
    }

    /**
     * 处理分类数据
     * @param array $param
     * @return array
     */
    public function get_class_list($param = []){
        $class = new ClassifyDao();
        $page_size = isset($param['page_size']) ? $param['page_size'] : 5;
        $result = $class->get_by_id($page_size);
        if($result && is_array($result)){
            $att = new attachmentDao();
            $class_img = new ClassifyimgDao();
            foreach($result['data'] as $k => $v){
                $class_img_id = $class_img->get_logo_by_class_id($v['id']);
                $result['data'][$k]['img_url'] = $att->getUrlAttr($class_img_id['img_id']);
            }
        }
        return $result;
    }

    //获取一条
    public function getByid($id){
        $classifyDao = new ClassifyDao();
        $classifyImaDao = new ClassifyimgDao();
        $row = $classifyDao->get($id);
        $classifyImgs = $classifyImaDao->where('classify_id',$row['id'])->join(['tb_attachment'=>'a'],'a.id = tb_classify_img.img_id')->select();
        if($classifyImgs){
            foreach ($classifyImgs as $img){
                if($img['type'] ==1){
                    $icon = $img['url'];
                }elseif ($img['type'] == 2){
                    $banner = $img['url'];
                }
            }
        }
        $domain = Config::get('qiniu.domain');
        $row['icon'] = empty($icon)?'':'http://'.$domain.'/'.$icon;
        $row['banner'] = empty($banner)?'':'http://'.$domain.'/'.$banner;
        return $row;
    }

    //修改分类图片(封面,图片)
    public function modifyImg($icon,$classifyId,$type = 1){
        $attachmentDao = new attachmentDao();
        $domain = Config::get('qiniu.domain');
        $file = substr(strstr($icon,$domain),strlen($domain)+1);
        $id = $attachmentDao->where('url',$file)->value('id');
        $classifyImgDao = new ClassifyimgDao();
        $row = $classifyImgDao->where(['classify_id'=>$classifyId,'type'=>$type])->find();
        if($row){
            $row['img_id'] = $id;
            return $row->save();
        }else{
            return $classifyImgDao->insert(['img_id'=>$id,'classify_id'=>$classifyId,'type'=>$type]);
        }
    }

    public function addOne($param){
        $icon = $param['icon'];
        $banner = $param['banner'];
        $param['create_date'] = date('Y-m-d H:i:s');
        $param['update_date'] = date('Y-m-d H:i:s');
        $classifyDao = new ClassifyDao();
        unset($param['icon']);
        unset($param['banner']);
        if(!$classifyDao->save($param)){
            return false;
        }
        $classifyId = $classifyDao->id;
        return $this->modifyImg($icon,$classifyId,1)&&$this->modifyImg($banner,$classifyId,2);

    }

    //获取商品所有分类
    public function itemClassify($id){
        $classifyItemDao = new ClassifyitemDao();
        $classifyIds = $classifyItemDao->getClassifyByItemid($id);
        $classifyDao = new ClassifyDao();
        $list = $classifyDao->getByIds($classifyIds);
        if(empty($list)){
            return ['total'=>0,'rows'=>[]];
        }else{
            return ['total'=>count($list),'rows'=>$list];
        }

    }

    //为商品添加分类
    public function addItemToClassify($itemid,$classifyid){
        $classifyItemDao = new ClassifyitemDao();
        $classifyIds = $classifyItemDao->getClassifyByItemid($itemid);
        if(in_array($classifyid,$classifyIds)){
            return true;
        }

        return $classifyItemDao->save(['item_id'=>$itemid,'classify_id'=>$classifyid]);
    }

    /**
     * 根据分类数据返回商品id数组
     * @return array
     */
    public function get_id_list($cateId)
    {
        $cateItem = new ClassifyItemDao();
        $data = $cateItem->get_id_list($cateId);
        $arr = array();
        if ($data && is_array($data))
            foreach ($data as $val) {
                $arr[] = $val['item_id'];
            }
        return $arr;
    }

}