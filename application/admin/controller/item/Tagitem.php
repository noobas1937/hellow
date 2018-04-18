<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/30 0030
 * Time: 下午 4:53
 */

namespace app\admin\controller\item;


use app\common\controller\Backend;
use gyo2o\model\Item;

class Tagitem extends Backend
{
    protected $model = null;

//    public function _initialize()
//    {
//        parent::_initialize();
//        $this->model = model('gyo2o\\dao\\ItemDao');
//
//    }

    //分类下商品列表
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $tagid = input('ids');

        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams('title');
            $itemModel = new Item();
            $result = $itemModel->getByTag($where,$sort,$order,$offset,$limit,$tagid);
            return json($result);
        }
        $this->assign('ids',$tagid);
        return $this->view->fetch();
    }

    public function del($ids = NULL,$tagid = NULL)
    {
        if(empty($ids || empty($tagid))){
            $this->error('删除失败');
        }

        $itemModel = new Item();
       if($itemModel->delClassigyItem($ids,$tagid)===false){
           $this->error('');
       }else{
           $this->success();
       }

    }
}