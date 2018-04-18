<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/19 0019
 * Time: 下午 3:38
 */

namespace gyo2o\model;


use gyo2o\BaseModel;
use gyo2o\dao\KeyWordDao;

class KeyWord extends BaseModel
{
    /*
   * 获取热门词汇
   * @Author: fuhaijuan
   * @Date:2016/08/03
   * */
    public function get_hot()
    {
        $KeyWord = new KeyWordDao();
        return $KeyWord->get_hot();
    }

    /*
     * 添加词汇到热门
     * @Author: fuhaijuan
     * @Date:2016/08/03
     * */
    public function add($word)
    {
        $KeyWord = new KeyWordDao();
        $words = $KeyWord->get_all();
        $temp = array();
        if ($words && is_array($words)) {
            foreach ($words as $val) {
                $temp[] = $val['word'];
            }
            $allString = implode('|', $temp);
            $out = array();
            preg_match_all("/$allString/", $word, $out);
            if ($out[0] && is_array($out[0])) {
                foreach ($out[0] as $val) {
                    $KeyWord->add_frequency($val);
                }
            }
        }
    }

}