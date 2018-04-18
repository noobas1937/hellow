<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/14 0014
 * Time: 下午 2:52
 */

namespace gyo2o\dao;


use think\Model;

class ClassicCasesDao extends Model
{
    protected $table = "tb_classic_cases";

    private function _get($where)
    {
        return $this->where($where)->order('seq desc')->select();
    }

    public function get_cases(){


        //     return $this->order('src desc')->limit('0,5')->select();

        //      return $this->limit('0,5')->select();
        return $this->order('seq desc')->limit('0,5')->select();



    }

    public function get_cases_byid($caseId){

        $where=array(

            'id'=>$caseId
        );


        return $this->where($where)->select();

    }

    /**
     * 将商品详情替换成可显示的内容
     * @param $str
     *
     * @return mixed
     */
    function detailsToshow($str)
    {
        preg_match_all('/#attach_(\d*)/', $str, $matchs);
        if ($matchs[1] && is_array($matchs[1])) {
            foreach ($matchs[1] as $val) {
                $replace[] = getAttachment($val);
            }
        }
        return str_replace($matchs[0], $replace, $str);
    }

}