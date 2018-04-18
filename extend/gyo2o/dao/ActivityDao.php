<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/11 0011
 * Time: 下午 3:07
 */

namespace gyo2o\dao;


use think\Model;
use think\Db;

class ActivityDao extends Model
{

    protected $table = "tb_activity";

    private $hot_yn = ['热门','不热门'];

    // 追加属性
    protected $append = ['city_name','province_name','hot_yn_text'];

    public function getHotyntextAttr($value,$data){
        return $this->hot_yn[$data['hot_yn']];
    }

    public function getHotyn(){
        return $this->hot_yn;
    }

    public function getProvincenameAttr($value,$data){
        return Db::table('tb_data_dictionary')->where('id',$data['province_id'])->find()['name'];
    }

    public function getCitynameAttr($value,$data){
        return Db::table('tb_data_dictionary')->where('id',$data['city_id'])->find()['name'];
    }

    public function getImgidAttr($data){
        $domain = \think\Config::get('qiniu.domain');
        $url = attachmentDao::where('id',$data)->value('url');
        return  'http://'.$domain.'/'.$url;

    }

    public function setImgidAttr($data){
        if(is_numeric($data)){
            return $data;
        }else{
            $domain = \think\Config::get('qiniu.domain');
            $file = substr(strstr($data,$domain),strlen($domain)+1);
            $attachment = new attachmentDao();
            $id = $attachment->where('url',$file)->value('id');
            return $id;
        }
    }
    public function get_by_hot($hot_yn,$cityid)
    {
        $map = array(
            'hot_yn' => $hot_yn,
            'city_id' => $cityid
        );
        return $this->_get($map);
    }

    private function _get($where)
    {
        return $this->where($where)->order('seq desc')->select();
    }
}