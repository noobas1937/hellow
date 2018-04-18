<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/4 0004
 * Time: 下午 3:32
 */

namespace gyo2o\dao;


use think\Model;

class attachmentDao extends Model
{
    protected $table = 'tb_attachment';

    /**
     * 根据图片id获取图片地址
     * @param $img_id
     * @return mixed|null|string
     */
    public function getUrlAttr($img_id){
        $map = ['id' => $img_id];
        $result = $this->where($map)->value('url');
        if(strpos($result,'http') !== false){
            return $result;
        }else{
            if($result){
                $domain = config('qiniu.domain');
                return 'http://'.$domain.'/'.$result;
            }else{
                return null;
            }
        }
    }

    public function getImgid($imgstr){
        $domain = config('qiniu.domain');
        $file = substr(strstr($imgstr,$domain),strlen($domain)+1);
        $id = $this->where('url',$file)->value('id');
        return $id;
    }
}