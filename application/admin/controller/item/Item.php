<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/5 0005
 * Time: 上午 10:31
 */

namespace app\admin\controller\item;


use app\common\controller\Backend;
use gyo2o\model\Item as ItemModel;

class Item extends Backend
{

    /**
     * 商品模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('gyo2o\\dao\\ItemDao');
        $this->assign('rec_yn',$this->model->getRecyn());
        $this->assign('is_hot',$this->model->getIshot());
        $this->assign('show_yn',$this->model->getShowyn());
        $this->assign('from_sale',$this->model->getFromsale());

    }

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);


        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams('title');
            $itemModel = new ItemModel();
            $result = $itemModel->getItems($where,$sort,$order,$offset,$limit);
            return json($result);
        }
//        $this->assign('ids',$tagid);
        return $this->view->fetch();
    }

    public function del($ids=""){
        //做软删除
        $itemModel = new ItemModel();
        if($itemModel->delByItemid($ids)===false){
            $this->error('error');
        }else{
            $this->success();
        }

    }

    public function add()
    {
        $productId = input('ids');
        if ($this->request->isPost())
        {
            parent::add();

        }
        $this->view->assign('productid',$productId);
        return $this->view->fetch();
    }

}