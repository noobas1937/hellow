<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/13 0013
 * Time: 下午 5:55
 */

namespace gyo2o\model;


use gyo2o\BaseModel;
use gyo2o\dao\attachmentDao;
use gyo2o\dao\TopicImgDao;
use think\view\driver\Think;

class TopicImg extends BaseModel
{

    public function getImgByTypeAndTpicid($type,$topicid){
        $topicImgDao = new TopicImgDao();
        $imgids = $topicImgDao->getTopicImgidsByTypeAndTopicid($type,$topicid);
        if(empty($imgids)){
            return '';
        }
        array_walk($imgids,[$this,'parseImg']);
        return implode(',',$imgids);
    }

    public function parseImg(&$img){
        $attachment = new attachmentDao();
        $img = $attachment->getUrlAttr($img);
    }

    public function modifyImg($img,$type,$id){
        $img = explode(',',$img);
        $imgs = $this->imagesToIds($img,$type,$id);
        $topicImgDao = new TopicImgDao();
        $oldImg = $topicImgDao->getTopicImgByTypeAndTopicid($type,$id);
        if(empty($oldImg)){
            //新增
           return $topicImgDao->saveAll($imgs);
        }else{
            return $topicImgDao->editImg($oldImg,$imgs);
        }
    }

    private function imagesToIds($imgs,$type,$topic_id){
        $attachmentDao = new attachmentDao();
        $domain = \Think\Config::get('qiniu.domain');
        $imgIds = [];
        foreach ($imgs as $img){
            $file = substr(strstr($img,$domain),strlen($domain)+1);
            $id = $attachmentDao->where('url',$file)->value('id');
            $imgIds[] = ['img_id'=>$id,'type'=>$type,'topic_id'=>$topic_id];
        }
        return $imgIds;
    }
}