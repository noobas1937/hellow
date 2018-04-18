<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6 0006
 * Time: 下午 5:47
 */

namespace app\admin\controller\item;


use app\common\controller\Backend;

class Product extends Backend
{
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('gyo2o\\dao\\ProductDao');


    }

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);


        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams('item_nm');
            $productModel = new \gyo2o\model\Product();
            $result = $productModel->getProducts($where,$sort,$order,$offset,$limit);
            return json($result);
        }
//        $this->assign('ids',$tagid);
        return $this->view->fetch();
    }

    public function edit($ids = NULL)
    {
        $productModel = new \gyo2o\model\Product();
        $row =  $productModel->getByid($ids);
        if (!$row)
            $this->error(__('No Results were found'));

        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                foreach ($params as $k => &$v) {
                    $v = is_array($v) ? implode(',', $v) : $v;
                }try {

                    $productModel->modifyCoverImg($params['coverimg'],$ids);
                    $productModel->modifyImg($params['img'],$ids,2);
                    $productModel->modifyImg($params['contentimg'],$ids,3);
//                    $classifyModel->modifyImg($params['banner'],$ids,2);
                    unset($params['coverimg']);
                    unset($params['contentimg']);
                    unset($params['img']);
                    unset($row['coverimg']);
                    unset($row['contentimg']);
                    unset($row['img']);
                    $params['update_date'] = date('Y-m-d H:i:s');
                    $result = $row->save($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($row->getError());
                    }
                } catch (think\exception\PDOException $e) {
                $this->error($e->getMessage());
                }
            }

            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    public function add()
    {
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                foreach ($params as $k => &$v)
                {
                    $v = is_array($v) ? implode(',', $v) : $v;

                }
                try {

                    $productModel = new \gyo2o\model\Product();
                    $result = $productModel->addOne($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error();
                    }
                }catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

}