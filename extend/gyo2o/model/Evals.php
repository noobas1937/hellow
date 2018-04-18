<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/19 0019
 * Time: 下午 1:39
 */

namespace gyo2o\model;


use gyo2o\BaseModel;
use gyo2o\dao\attachmentDao;
use gyo2o\dao\EvalDao;
use gyo2o\dao\ItemImgDao;
use gyo2o\dao\EvalImgDao;
use gyo2o\dao\ItemDao;
use gyo2o\dao\TborderDao;

class Evals extends BaseModel
{
    const GOODS = 0;
    const READ = 1;
    const UN_READ = 0;
    const COMMENT = 0;
    const REPLY = 1;
    const UN_DEL = 0;

    /**
     * 添加评论
     * @param $itemId
     * @param $comment
     *
     * @return array
     */
    public function add_eval($post, $itemId, $sn2, $create_date)
    {
        $Item = new ItemDao();
        $Eval = new EvalDao();
        $EvalImg = new EvalImgDao();
        $Order = new TborderDao();
        if ($Item->is_exist_by_id($itemId)) {
            $data = array(
                'user_id' => $post['user_id'],
                'rel_id' => $itemId,
                'rel_type' => self::GOODS,
                'content' => $post['content'],
                'create_date' => date('Y-m-d H:i:s'),
                'r_yn' => self::UN_READ,
                'type' => self::COMMENT,
                'stars_item' => empty($post['stars_item'])?0:$post['stars_item'],
                'stars_service' => empty($post['stars_service'])?0:$post['stars_service'],
                'stars_rider' => empty($post['stars_rider'])?0:$post['stars_rider'],
                'sn2' => $sn2,
            );

            if(false != $create_date){
                $data['create_date'] = $create_date;
            }

            $Eval->save($data);
            $eval_id = $Eval->id;

            if ($eval_id == true) {
                if(!empty($post['img_id'])){
                    $img_id = explode(",", $post['img_id']);
                    foreach ($img_id as $key => $val) {
                        if ($val == true) {
                            if (is_numeric($val)) {
                                $eval_img_data[] = array(
                                    "img_id" => $val,
                                    "eval_id" => $eval_id
                                );
                            }
                        }
                    }

                    $EvalImg->saveAll($eval_img_data);

                }

                $Order->set_sn2($sn2, 3);
            }
            return array('status' => 'success', 'code' => 3, 'msg' => '评价成功', 'output' => null, 'url' => Url('member/comment/finish',array('itemid'=>$itemId)));
        }
        return array('status' => 'failer', 'code' => 4, 'msg' => '评价失败', 'output' => null);
    }

    public function get_list($userid,$page,$pageSize)
    {
        $eval = new EvalDao();
        $evalimg = new EvalImgDao();
        $item = new ItemDao();
        $itemimg = new ItemImgDao();
        $msg_data = $eval->get_by_msg($userid,$page,$pageSize);
        $attachment = new attachmentDao();
        foreach ($msg_data as $k => $v) {
            if ($v['rel_type'] == 0) {
                $msg_data[$k]['item'] = $item->get_by_item($v['rel_id']);
                $itemImgId = $itemimg->get_item_cover($msg_data[$k]['item']['product_id']);
                $msg_data[$k]['item_img'] = $attachment->getUrlAttr($itemImgId['id']);
            }
            $evalImgs = $evalimg->get_by_eval($v['id']);
            $temp = [];
            if($evalImgs){
                foreach ($evalImgs as $key => $value){
                    $temp[] = $attachment->getUrlAttr($value['id']);
                }
            }
            $msg_data[$k]['eval_img'] = $temp;
            $msg_data[$k]['reply'] = $eval->get_by_replay($v['id']);
        }
        return $msg_data;
    }

    /**
     * 评论条数
     * @param $relId
     *
     * @return mixed
     */
    public function count($relId){
        $eval = new EvalDao();
        $Item = new ItemDao();
        $item_data = $Item->get_by_item($relId);
        $product_data = $Item->get_by_productid($item_data['product_id']);
        $product_id = '';
        foreach($product_data as $k=>$v){
            if(count($product_data)-1 > $k){
                $product_id .= $v['id'].',';
            }else{
                $product_id .= $v['id'];
            }
        }
        return $eval->get_count($product_id);
    }

    public function reply($evalId, $comment)
    {
        $Eval = new EvalDao();
        if ($Eval->exist($evalId)) {
            $data = array(
                //'user_id'=>$this->user_id,
                'rel_id' => $evalId,
                //'rel_type'=>self::GOODS,
                'content' => $comment,
                'create_date' => date('Y-m-d H:i:s'),
                'r_yn' => self::UN_READ,
                'type' => self::REPLY
            );
            if ($Eval->save($data))
                return array('status' => 'success', 'code' => 3, 'msg' => '回复评论成功', 'output' => null);
        }
        return array('status' => 'failer', 'code' => 4, 'msg' => '回复评论失败', 'output' => null);
    }

    /**
     * 返回评论图片数组
     * @param $evalId
     *
     * @return array
     */
    public function get_imgs($evalId){
        $evalImg = new EvalImgDao();
        $data = $evalImg->get_by_eval($evalId);
        $imgs = array();
        if($data && is_array($data)) {
            foreach($data as $val){
                $imgs[] = getAttachment($val['img_id']);
            }
        }
        return $imgs;
    }

    /**
     * 返回评论的数据
     * @param $rel_id
     *
     * @return $eval
     * @return array
     */
    public function get_list_by_relid($rel_id){
        $Eval = new EvalDao();
        $result   =$Eval->get_by_replay($rel_id);
        return $result;
    }

    /**
     * 返回评论的数据的id
     * @param $user_id
     *
     * @return $id
     * @return array
     */
    public function get_list_id($userid){
        $Eval = new EvalDao();
        $result1   =$Eval->get_by_msg($userid);
        foreach ($result1 as $k => $v) {
            if($result1[$k]['user_id']==$userid){
                $result[$k]['id'] =$result1[$k]['id'];

            }

        }
        return $result;
    }


    /**
     * 根据$userid修改后台回复的消息的已读未读状态
     * @param $user_id
     *@param $status
     *
     * @return $result
     * @return bool
     */
    public function save_r_yn($userid){
        $Eval = new EvalDao();
        $result1 = $this->get_list_id($userid);
        //       var_dump($result1);
        //如果评论的主键--id存在的话。
        if($result1){
            //遍历数据
            foreach ($result1 as $k => $v) {
                //取出评论的rel_id
                $re_id=$result1[$k]['id'];
                //              var_dump($re_id);
                if($re_id){
                    //通过rel_id来去取出评论信息。
                    $result2=$this->get_list_by_relid($re_id);
                    //如果评论信息存在的话，更改数据库。将里面数据变成1
                    if($result2){
                        $re_id=$result2[$k]['re_id'];
                        $result= $Eval->save_r_yn($re_id);
                    }
                    //                  var_dump($result2);
                }


            }
        }

        return $result;

    }

    /**
     * 得到当前已读未读状态的数量
     * @param $user_id
     *@param $status
     *
     * @return $id
     */
    public function get_r_yn_count($status,$userid){
        $Eval = new EvalDao();
        $result1 = $this->get_list_id($userid);
        $re_id = array();
        foreach ($result1 as $k => $v) {
            //取出评论的rel_id
            $re_id[]=$result1[$k]['id'];
            //           var_dump($re_id);
        }
        return $Eval->get_r_yn_count($status,$re_id);


    }

    public function getUserCount($userid){
        $Eval = new EvalDao();
        return $Eval->getCountByUserId($userid);
    }
}