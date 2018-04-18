<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/30 0030
 * Time: 下午 2:18
 */

namespace gyo2o\dao;

use think\Model;

class ClassifyDao extends Model
{
    private $rec_yn = ['不推荐','推荐'];
    protected $table = 'tb_classify';
    protected $append = ['rec_yn_text'];
    const REC_Y = 1;

    public function getRec(){
        return $this->rec_yn;
    }
    public function getRecyntextAttr($valeu,$data){
        return $this->rec_yn[$data['rec_yn']];
    }

    //按条件获取记录数
    public function getClassifyNumber($where)
    {
        return $this->where($where)->count();
    }

    //按条件获取记录
    public function getClassifys($where,$sort,$order,$offset,$limit)
    {
        return $this->where($where)->order($sort,$order)->limit($offset,$limit)->select();
    }

    //根据ID集获取列表
    public function getByIds($ids)
    {
        return $this->where('id','in',$ids)->select();
    }

    public function get_by_id($page_size,$order = 'seq desc'){
        $map = [
            'rec_yn' => self::REC_Y
        ];
        return $this->where($map)->order($order)->paginate($page_size)->toArray();
    }

}