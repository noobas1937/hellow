<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/28 0028
 * Time: 下午 3:14
 */

namespace gyo2o\dao;

use app\common\model\Attachment;
use think\Config;
use think\Model;

class PackageDao extends Model
{
    protected $name = 'package';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;

    // 追加属性
    protected $append = [

    ];

    //自动完成
    protected $update = ['package_image'];

    public function setPackageImageAttr($data){
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
    public function getPackageImageAttr($data){
        $domain = \think\Config::get('qiniu.domain');
        $url = \app\common\model\Attachment::where('id',$data)->value('url');
        return  'http://'.$domain.'/'.$url;

    }
    public function getCount($where,$sort,$order){
        return $this->where($where)->order($sort, $order)->count();
    }

    public function getPackages($where,$sort,$order,$offset,$limit){
        return $this->where($where)
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->select();
    }

    public function getLastPackage($nex_monday){
        return $this->where("start_time = '$nex_monday'")->select();

    }


}