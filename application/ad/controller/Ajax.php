<?php
/**
 * Created by PhpStorm.
 * User: liuwen
 * Date: 17/7/19
 * Time: 上午9:28
 */

namespace app\ad\controller;

use app\common\controller\Api;
use gyo2o\model\Ad;
use gyo2o\model\Headline;


class Ajax extends Api
{

    /**
     * 获取广告位
     * @return \think\response\Json
     */
    public function ad_list(){
        $param = input('post.');
        $validate = $this->validate($param,'ad/AdValidate.ad_list');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $ad = new Ad();
            $result = $ad->ad_list($param);
            $result = $this->return_list($result,'查询成功','暂无数据');
        }else{
            $result = $return_validate;
        }
        return json($result);
    }

    /**
     * 头条新闻列表
     * @return \think\response\Json
     */
    public function news_list(){
        $param = input('post.');
        $validate = $this->validate($param,'ad/AdValidate.news_list');
        $return_validate = $this->return_validate($validate);
        if($return_validate === true){
            $ad = new Headline();
            $result = $ad->get_by_hot($param);
            $result = $this->return_list($result,'查询成功','暂无数据');
        }else{
            $result = $return_validate;
        }
        return json($result);
    }


}
