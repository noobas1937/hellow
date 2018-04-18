<?php

namespace gyo2o\model;

use gyo2o\BaseModel;
use gyo2o\dao\AccountDao;
use gyo2o\dao\attachmentDao;
use gyo2o\dao\EvalDao;
use gyo2o\dao\EvalImgDao;
use gyo2o\dao\ItemDao;
use gyo2o\dao\UserInfoDao;

class ItemEval extends BaseModel
{
    public function get_item_eval($param){
        $Eval = new EvalDao();
        $UserInfo = new Account();
        $Item = new ItemDao();
        $item_data = $Item->get_bt_item_id($param['item_id']);
        $product_data = $Item->get_by_product($item_data['product_id']);
        $product_id = '';
        foreach($product_data as $k=>$v){
            if(count($product_data)-1 > $k){
                $product_id .= $v['id'].',';
            }else{
                $product_id .= $v['id'];
            }
        }
        $page_size = isset($param['page_size']) ? $param['page_size'] : 5;
        $data = $Eval->get_by_item($product_id,$page_size);

        $EvalImg = new EvalImgDao();
        $att = new attachmentDao();
        if ($data['data'] && is_array($data['data'])) {
            foreach ($data['data'] as $key => $val) {
                $userInfo = $UserInfo->get_by_user_id($val['user_id']);
                if($userInfo){
                    $data['data'][$key]['user_info'] = $userInfo;
                    $data['data'][$key]['user_info']['img_url'] = $att->getUrlAttr($data['data'][$key]['user_info']['head_img']);
                }
                $images = $EvalImg->get_by_eval($val['id']);
                if ($images && is_array($images)){
                    $data['data'][$key]['images'] = $images;
                    foreach($data['data'][$key]['images'] as $k => $v ){
                        $v['img_url'] = $att->getUrlAttr($v['img_id']);
                    }
                }
                $d = $Eval->get_by_replay($val['id']);
                if ($d && is_array($d))
                    $data['data'][$key]['reply'] = $d[0]['content'];
            }
        }
        return $data;
    }

    public function getEvalsByItemId($where, $sort, $order, $offset, $limit,$ids){
        $evalDao = new EvalDao();
        $total = $evalDao->getTotal($where,$ids);
        if(empty($total)){
            return ['rows'=>[],'total'=>0];
        }
        $rows = $evalDao->getEvalsByItemId($where, $sort, $order, $offset, $limit,$ids);
        array_walk($rows,[$this,'addtionUserAcconutName']);
        array_walk($rows,[$this,'addtionEvalImg']);
        return ['rows'=>$rows,'total'=>$total];
    }

    private function addtionUserAcconutName(&$row){
        $accountDao = new UserInfoDao();
        $row['user_id'] = $accountDao->getNicknaemByUserid($row['user_id']);
    }

    private function addtionEvalImg(&$row){
        $evaImgDao = new EvalImgDao();
        $attachment = new attachmentDao();
        $imgids = $evaImgDao->getEvalImgIds($row['id']);
        if(empty($imgids)){
            $row['img'] = '';
        }else{
            foreach ($imgids as $imgid){
                $tem[] = $attachment->getUrlAttr($imgid);
            }
            $row['img'] = implode(',',$tem);
        }
    }

    public function getEvalData($id){
        $evaImgDao = new EvalDao();
        $data['content'] = $evaImgDao->getContentById($id);
        $data['replaycontent'] = $evaImgDao->getReplyContentById($id);
        return $data;
    }

    public function replay($id,$content){
        $evalDao = new EvalDao();
        $evalReplay = $evalDao->getReplayById($id);
        if(empty($evalReplay)){
            $data = ['rel_id'=>$id,'type'=>1,'content'=>$content];
            return $evalDao->save($data);
        }
        $evalReplay->content = $content;
        return $evalReplay->save();
    }

}
