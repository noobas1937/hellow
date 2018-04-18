<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/19 0019
 * Time: 下午 3:18
 */

namespace app\item\controller;


use app\common\controller\Api;
use gyo2o\model\Search as SearchModel;
use gyo2o\model\KeyWord;

class Search extends Api
{
    /**
     * 搜索页面
     */
    public function index()
    {

        $uid = $this->request->post('user_id');
        if($uid){
            $Search = new SearchModel();
            $historySearch = $Search->get_my_history($uid);
        }else{
            $historySearch = array();
        }
        $KeyWord =  new KeyWord();
        $hot = $KeyWord->get_hot();
        return json(['status'=>'success','code'=>3,'data'=>['history'=>$historySearch,'hot'=>$hot],'msg'=>'']);


    }

    /**
     * 删除用户搜索历史记录
     */
    public function del_search_all(){
        $uid = $this->request->post('user_id');
        if(empty($uid)){
            return json([["status" => "failer", "code" => 4, "msg" => "缺少参数"]]);
        }
        $Search = new SearchModel();
        if($Search->del_all_by_uid($uid))
            $result = array('status' => 'success', 'code' => 3, 'msg' => '删除成功', 'output' => array());
        else
            $result = array('status' => 'failer', 'code' => 4, 'msg' => '删除失败', 'output' => null);
        return json($result);
    }

    public function search()
    {
        $words = $this->request->post('words');
        $uid = $this->request->post('user_id');
        $page = $this->request->post('page') ? $this->request->post('page') : 1;
        $pagesize = $this->request->post('pagesize') ? $this->request->post('pagesize') : 10;
        $Search = new SearchModel();
        if ($page <= 1 && !empty($words)) {
            $Search->add($words, $uid);
            $KeyWord = new KeyWord();
            $KeyWord->add($words);
        }
        $data = $Search->search_item_by_words($words, $page,$uid,$pagesize);
        if($data){
            $page = ['total'=>$data['count'],'last_page'=>ceil($data['count']/$pagesize)];
        }
        return json(['status'=>'success','code'=>3,'msg'=>'','data'=>$data,'page'=>$page]);
    }
}