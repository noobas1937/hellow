<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/13 0013
 * Time: 下午 5:21
 */

namespace gyo2o\dao;


use think\Model;

class TopicImgDao extends Model
{

    protected $table = 'tb_topic_img';

    /**
     * 根据topicid获取图片信息
     * @Author: fuhaijuan
     * @Date:2016/08/13
     * @param $topicId
     * @param $type
     *
     * @return mixed
     */
    public function get_by_topicId($topicId, $type)
    {
        return $this->where(array('topic_id' => $topicId, 'type' => $type))->select();
    }

    /**
     * 添加图片
     * @param $imgId
     * @param $relId
     * @param $type
     *
     * @return mixed
     */
    public function add_img($imgId, $relId, $type)
    {
        $data = array(
            'img_id' => $imgId,
            'topic_id' => $relId,
            'type' => $type
        );
        return $this->save($data);
    }

    public function getTopicImgidsByTypeAndTopicid($type,$topicid){
        return $this->where('type',$type)->where('topic_id',$topicid)->column('img_id');
    }

    public function getTopicImgByTypeAndTopicid($type,$topicid){
        return $this->where('type',$type)->where('topic_id',$topicid)->select();
    }

    public function editImg($oldImg,$newImg){
        $breakStep = 0;
        $i = 0;
        $this->startTrans();
        foreach ($oldImg as $img){
            if(empty($newImg)){
                $breakStep = $i;
                break;
            }
            $img->img_id = $newImg[0]['img_id'];
            if($img->save()===false){
                $this->rollback();
                return false;
            }
            array_shift($newImg);
            $i++;
        }
        if(!empty($newImg)){
            if($this->saveAll($newImg)===false){
                $this->rollback();
                return false;
            }
        }

        if($breakStep>0){
            for($i = $breakStep;$i<count($oldImg);$i++){
                if($oldImg[$i]->delete()===false){
                    $this->rollback();
                    return false;
                }
            }
        }

        $this->commit();
        return true;
    }
}