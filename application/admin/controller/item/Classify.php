<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/30 0030
 * Time: 上午 11:26
 */

namespace app\admin\controller\item;


use app\common\controller\Backend;

class Classify extends Backend
{
    /**
     * 模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('gyo2o\\dao\\ClassifyDao');
        $this->assign('rec_yn',$this->model->getRec());

    }

    public function index()
    {
        //设置过滤方法
        //设置过滤方法
        $this->request->filter(['strip_tags']);

        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams('id');
            $classifyModel = new \gyo2o\model\Classify();
            $result = $classifyModel->getClassify($where,$sort,$order,$offset,$limit);
            return json($result);
        }
        return $this->view->fetch();
    }

    public function edit($ids = NULL)
    {
        $classifyModel = new \gyo2o\model\Classify();
        $row =  $classifyModel->getByid($ids);
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

                    $classifyModel = new \gyo2o\model\Classify();
                    $classifyModel->modifyImg($params['icon'],$ids,1);
                    $classifyModel->modifyImg($params['banner'],$ids,2);
                    unset($params['icon']);
                    unset($params['banner']);
                    $params['update_date'] = date('Y-m-d H:i:s');
                    unset($row['icon']);
                    unset($row['banner']);
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

                    $classifyModel = new \gyo2o\model\Classify();
                    $result = $classifyModel->addOne($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($this->model->getError());
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