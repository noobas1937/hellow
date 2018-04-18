<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/5 0005
 * Time: 下午 5:33
 */

namespace app\admin\controller\item;


use app\common\controller\Backend;
use gyo2o\model\Item;

class Itemclassify extends Backend
{

    public function index($ids=null)
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);

        if ($this->request->isAjax())
        {
            $classifyModel = new \gyo2o\model\Classify();
            $result = $classifyModel->itemClassify($ids);
            return json($result);
        }

        $this->assign('ids',$ids);
        return $this->view->fetch();
    }

    //添加分类
    public function add($itemid=null)
    {

        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            $params['item_id'] = $itemid;
            if ($params && $itemid)
            {
                foreach ($params as $k => &$v)
                {
                    $v = is_array($v) ? implode(',', $v) : $v;
                }

                $classifyModel = new \gyo2o\model\Classify();
                if($classifyModel->addItemToClassify($itemid,$params['classify_id'])!==false){
                    $this->success();
                }else{
                    $this->error('error');
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    //删除分类
    public function del($itemid=null,$ids=null){


        if(empty($ids)&&empty($itemid)){
            $this->error('Parameter can not be empty');
        }
        $itemModel = new Item();
        if($itemModel->delClassigyItem($itemid,$ids)===false){
            $this->error('');
        }else{
            $this->success();
        }


    }


}