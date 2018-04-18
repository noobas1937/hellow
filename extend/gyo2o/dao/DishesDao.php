<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/28 0028
 * Time: 下午 2:18
 */

namespace gyo2o\dao;


use app\common\model\Attachment;
use think\Config;
use think\Model;

class DishesDao extends Model
{
    protected $name = 'dishes';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    // 追加属性
    protected $append = [
        'dishes_type_text',
        'dishes_status_text'
    ];

    //自动完成
    protected $update = ['dishes_image'];

    public function getDishesImageAttr($data){
        $domain = \think\Config::get('qiniu.domain');
        $url = \app\common\model\Attachment::where('id',$data)->value('url');
        return  'http://'.$domain.'/'.$url;

    }

    public function setDishesImageAttr($data){
        if(is_numeric($data)){
            return $data;
        }else{
            $domain = Config::get('qiniu.domain');
            $file = substr(strstr($data,$domain),strlen($domain)+1);
            $attachment = new Attachment();
            $id = $attachment->where('url',$file)->value('id');
            return $id;
        }
    }
    public function getDishestypelist()
    {
        return ['主食' => __('主食'),'饮料' => __('饮料'),'甜品' => __('甜品')];
    }

    public function getDishesstatuslist()
    {
        return ['正常' => __('正常'),'关闭' => __('关闭')];
    }


    public function getDishestypeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['dishes_type'];
        $list = $this->getDishestypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getDishesstatusTextAttr($value, $data)
    {
        $value = $value ? $value : $data['dishes_status'];
        $list = $this->getDishesstatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    //获取所有菜品
    public function getAllDishes(){
        return $this->select();
    }

    //根据ID获取餐品名
    public function getDishesName($id){
       $dishe = $this->where("id=$id")->field('name')->find();
       if($dishe){
           return $dishe['name'];
       }
       return null;
    }

    //按条件计算总条数
    public function getTotal($where,$sort,$order){
        return $this->where($where)
            ->order($sort, $order)
            ->count();
    }

    //按条件获取菜品列表
    public function getDishes($where,$sort,$order,$offset,$limit){
        return $this->where($where)
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->select();
    }

    //按ID获取菜品
    public function getDishesById($id){
        return $this->where("Id=$id")->find();
    }

}