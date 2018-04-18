<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7 0007
 * Time: 下午 5:57
 */

namespace gyo2o\dao;

use think\Model;
use think\Db;
use think\view\driver\Think;

class AdDao extends Model
{
    // 表名
    protected $table = 'tb_ad';

    private $show_yn = ['显示','不显示'];
    private $link = ['1'=>'直接转跳','2'=>'其它'];

    // 追加属性
    protected $append = ['city_name','province_name','show_yn_text','link_text'];

    public function getProvincenameAttr($value,$data){
        return Db::table('tb_data_dictionary')->where('id',$data['province_id'])->find()['name'];
    }

    public function getCitynameAttr($value,$data){
        return Db::table('tb_data_dictionary')->where('id',$data['city_id'])->find()['name'];
    }

    public function getShowyntextAttr($value,$data){
        return $this->show_yn[$data['show_yn']];
    }

    public function getLinktextAttr($value,$data){
        return $this->link[$data['link']];
    }

    public function getShow_yn(){
        return $this->show_yn;
    }

    public function getLink(){
        return $this->link;
    }


    /**
     * 根据type与城市查询数据
     * @param $type [类型]
     * @param $city_id [城市id]
     * @param $pageSize [分页数]
     * @return array|false
     */
    public function get_type($type,$city_id,$pageSize = 5){
        $map = [
            'type' => $type,
            'city_id' => $city_id
        ];
        return $this->where($map)->paginate($pageSize)->toArray();
    }

    public function getTotal($where){
        return $this->where($where)->count();
    }

    public function getAds($where,$sort,$order,$offset,$limit){
        return $this->where($where)->order($sort,$order)->limit($offset,$limit)->select();
    }

    public function getById($id){
        return $this->where('id',$id)->find();
    }

}
